<?php
class MediaModel extends GummModel {
    
    /**
     * @var array
     */
    public $inRelation = array('Post');
    
    /**
     * @var bool
     */
    public $mergeOnSave = false;
    
    /**
     * @var array
     */
    private $_mimeTypes = array(
        'image' => array('image/png', 'image/jpeg', 'image/gif'),
        'video' => array('video/embed', 'video/vimeo', 'video/youtube', 'video/screenr'),
    );
    
    /**
     * @var array
     */
    private $_customSchema = array(
        'type' => '',
        'guid' => '',
    );
    
    public $wpFilters = array(
        'wp_get_attachment_url' => array('func' => 'getMediaUrl', 'priority' => 10, 'args' => 2),
    );
    
    /**
     * @param array $data
     * @return bool
     */
    public function updateMediaFields($data=null) {
        $id = isset($data['id']) ? $data['id'] : $this->id;
        if (isset($data['id'])) unset($data['id']);

        $errors = false;
        
        $post = $_post = get_post($id, ARRAY_A);

        if ( isset($data['post_content']) )
            $post['post_content'] = $data['post_content'];
        if ( isset($data['post_title']) )
            $post['post_title'] = $data['post_title'];
        if ( isset($data['post_excerpt']) )
            $post['post_excerpt'] = $data['post_excerpt'];
        if ( isset($data['menu_order']) )
            $post['menu_order'] = $data['menu_order'];

        $post = apply_filters('attachment_fields_to_save', $post, $data);

        if ( isset($data['image_alt']) ) {
            $image_alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            if ( $image_alt != stripslashes($data['image_alt']) ) {
                $image_alt = wp_strip_all_tags( stripslashes($data['image_alt']), true );
                // update_meta expects slashed
                update_post_meta( $id, '_wp_attachment_image_alt', addslashes($image_alt) );
            }
        }
        
        if ( isset($data['url']) ) {
            $image_url = get_post_meta($id, '_gumm_attachment_image_link', true);
            if ( (!is_array($image_url)) || (is_array($image_url && !isset($image_url['url']))) ) {
                $image_url = array('url' => '', 'button' => '');
            }
            if (isset($data['link_button'])) {
                $image_url['button'] = stripslashes($data['link_button']);
            }
            
            if ( $image_url['url'] != stripslashes($data['url']) ) {
                $image_url['url'] = addslashes(wp_strip_all_tags( stripslashes($data['url']), true ));
                $image_url['button'] = addslashes(stripslashes($image_url['button']));
                
                // update_meta expects slashed
                update_post_meta( $id, '_gumm_attachment_image_link', $image_url );
            }
        }

        if ( isset($post['errors']) ) {
            $errors[$attachment_id] = $post['errors'];
            unset($post['errors']);
        }

        if ( $post != $_post )
            wp_update_post($post);

        foreach ( get_attachment_taxonomies($post) as $t ) {
            if ( isset($data[$t]) )
                wp_set_object_terms($id, array_map('trim', preg_split('/,+/', $data[$t])), $t, false);
        }
        
        return ($errors) ? false : true;
    }
    
    /**
     * @return array
     */
    public function getMediaMimeTypes() {
        
        $mimeTypes = array();
        foreach ($this->_mimeTypes as $key => $mimeTypeValues) {
            $mimeTypes = array_merge($mimeTypes, $mimeTypeValues);
        }
        
        return $mimeTypes;
    }
    
    /**
     * @param key
     * @return array
     */
    public function getMediaMimeType($key) {
        $key = strtolower($key);
        $mimeTypes = $this->getMediaMimeTypes();
        
        return (isset($this->_mimeTypes[$key])) ? $this->_mimeTypes[$key] : array();
    }
    
    public function isVideo($attachment) {
        return in_array($attachment->post_mime_type, $this->_mimeTypes['video']);
    }
    
    /**
     * Setter
     * 
     * @param object $attachment
     * @return void
     */
    public function setMediaFieldsByType(&$attachment) {
        $_fields                = $this->_customSchema;
        $_fields['guid']        = $attachment->guid;
        $_fields['permalink']   = $attachment->guid;
        
        foreach ($this->_mimeTypes as $mediaType => $mimeTypes) {
            if (in_array($attachment->post_mime_type, $mimeTypes)) {
                if ($mediaType == 'video' && $this->isVideo($attachment)) {
                    $_fields['guid'] = $attachment->post_content;
                    $_fields['permalink'] = $attachment->post_excerpt;
                    $_fields['post_content'] = '';
                }
                $_fields['type'] = $mediaType;
                break;
            }
        }
        $attachment = (object) Set::merge($attachment, $_fields);
    }
    
    /**
     * Attempt to save an embedded attachment
     * 
     * @param string $embedString
     * @return int post id on success | 0 on failure
     */
    public function saveVideo($embedString) {
        $data = $this->getVideoData($embedString);
        // if (!$data['id']) $data['id'] = 'external-video-' . uniqid();
        $attachmentData = array(
            'post_mime_type' => $data['mimeType'],
            'post_content' => $data['embedCode'],
            'post_status' => 'inherit',
            'post_excerpt' => $data['guid'],
            'post_title' => $data['provider'] && $data['id'] ? $data['provider'] . '-' . $data['id'] : 'external-video-' . uniqid()
        );
        // $attachment = array(
        //    'post_mime_type' => 'video/embed',
        //    'post_title' => 'external-video-' . uniqid(),
        //    'post_content' => $embedString,
        //    'post_status' => 'inherit'
        // );
        return wp_insert_attachment($attachmentData);
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function delete($id) {
        return wp_delete_attachment($id);
    }
    
    /**
     * Attempts to cleverly find and set data for embed string or url
     * Currently working providers: Vimeo, Youtube, Screenr
     * 
     * @return array
     */
    public function getVideoData($embedString) {
        $data = array(
            'id' => false,
            'provider' => false,
            'mimeType' => 'video/embed',
            'guid' => '',
            'embedCode' => $embedString,
        );

        /**
         * vimeo | youtube | screenr videos matching rules
         */
        if (preg_match_all("'player.vimeo.com/video/(.*)[\?|\"]'iU", $embedString, $out)) {
            $videoId = $out[1][0];
            $data = array(
                'id' => $videoId,
                'provider' => 'vimeo',
                'mimeType' => 'video/vimeo',
                'guid' => 'https://vimeo.com/' . $videoId,
                'embedCode' => $embedString,
            );
        } elseif (preg_match_all("'youtube.com/embed/(.*)[\?|\"]'iU", $embedString, $out)) {
            $videoId = $out[1][0];
            $data = array(
                'id' => $videoId,
                'provider' => 'youtube',
                'mimeType' => 'video/youtube',
                'guid' => 'https://www.youtube.com/watch?v=' . $videoId,
                'embedCode' => $embedString,
            );
        } elseif (preg_match_all("'screenr.com/embed/(.*)[\?|\"]'iU", $embedString, $out)) {
            $videoId = $out[1][0];
            $data = array(
                'id' => $videoId,
                'provider' => 'screenr',
                'mimeType' => 'video/screenr',
                'guid' => 'https://www.screenr.com/' . $videoId,
                'embedCode' => $embedString,
            );
        } elseif (preg_match_all("'vimeo.com/(.*)(\?|$)'iU", $embedString, $out)) {
            $videoId = $out[1][0];
            $data = array(
                'id' => $videoId,
                'provider' => 'vimeo',
                'mimeType' => 'video/vimeo',
                'guid' => 'https://vimeo.com/' . $videoId,
                'embedCode' => '<iframe width="100%" height="100%" src="https://player.vimeo.com/video/' . $videoId . '?title=0&byline=0&portrait=0&badge=0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
            );
        } elseif (preg_match_all("'youtube.com/watch\?v=(.*)(\&|$)'iU", $embedString, $out)) {
            $videoId = $out[1][0];
            $data = array(
                'id' => $videoId,
                'provider' => 'youtube',
                'mimeType' => 'video/youtube',
                'guid' => 'https://www.youtube.com/watch?v=' . $videoId,
                'embedCode' => '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
            );
        } elseif (preg_match_all("'screenr.com/(.*)(\?|$)'iU", $embedString, $out)) {
            $videoId = $out[1][0];
            $data = array(
                'id' => $videoId,
                'provider' => 'screenr',
                'mimeType' => 'video/screenr',
                'guid' => 'https://www.screenr.com/' . $videoId,
                'embedCode' => '<iframe width="100%" height="100%" src="https://www.screenr.com/embed/' . $videoId . '" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
            );
        }
        
        $data['error'] = (!$data['id']) ? true : false;
        
        return $data;
    }
    
    // ======= //
    // FILTERS //
    // ======= //
    
    /**
     * WordPress Filter Hook to get correct url for the custom video/embed mime type
     * 
     * @param string $url
     * @param int $postId
     * @return string
     */
    public function getMediaUrl($url, $postId) {
        if (!$url) {
            $post = $this->Post->findById($postId);
            if ($post->type == 'video') {
                $url = $post->guid;
            }
        }
        
        return $url;
    }

}
?>