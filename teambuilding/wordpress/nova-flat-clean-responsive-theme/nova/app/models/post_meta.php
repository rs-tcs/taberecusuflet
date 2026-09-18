<?php
class PostMetaModel extends GummModel {
    private $_schema = array(
        'post_loop_dimensions' => array('rows' => 1, 'cols' => 1),
        'testimonial_author' => array('name' => '', 'occupation' => ''),
        'post_quote' => array('content' => '', 'author' => ''),
        'post_link' => '#',
        'staff' => array(
            'postmeta' => array(
                'staff_member_icon' => '',
                'follow_link' => '',
                'social_networks_url' => array(
                    'twitter_url' => '',
                    'facebook_url' => '',
                    'google-plus_url' => '',
                    'linkedin_url' => '',
                    'printerest_url' => '',
                    'github_url' => '',                    
                ),
            ),
        ),
        'testimonial' => array(
            'postmeta' => array(
                'testimonial_author' => '',
                'testimonial_occupation' => '',
            ),
        ),
        'event' => array(
            'postmeta' => array(
                'event_rating' => 0,
                'event_organizer_name' => '',
                'event_organizer_link' => '',
                'event_shortinfo' => '',
            ),
        ),
        'page' => array(
            'postmeta' => array(
                'page_icon' => 'none',
                'subheading' => '',
                'heading_display' => 'true',
                'head_info_display' => 'true',
                'head_detail_bar_display' => 'true',
                'heading_search_display' => 'false',
                'heading_social_display' => 'false',
                'header_settings' => 'global',
                'heading_box_settings' => 'global',
            ),
        ),
        'partner' => array(
            'postmeta' => array(
                'url' => '#'
            ),
        ),
        'portfolio' => array(
            'postmeta' => array(
                'project_link_title' => '',
                'project_link_url' => '',
            ),
        ),
        'single' => array(
            'postmeta' => array(
                'page_icon' => 'none',
                'subheading' => '',
                'heading_display' => 'true',
                'heading_search_display' => 'false',
                'heading_social_display' => 'false',
                'featured_image_single_display' => 'true',
                'header_settings' => 'global',
                'heading_box_settings' => 'global',
            ),
        ),
    );
    
    public $inRelation = array('Post');
    
    /**
     * @param int $postId
     * @param string $metaId
     * @param bool $single
     * @return mixed
     */
    public function find($postId, $metaId, $single=true) {
        $metaId = $this->gummOptionId($metaId, true);

        $meta = false;
        if (strpos($metaId, '.') !== false) {
            $parts = explode('.', $metaId);
            $rootId = array_shift($parts);
            
            $rootMetaData = get_post_meta($postId, $rootId, $single);
            $metaXPath = implode('.', $parts);
            
            $meta = Set::classicExtract($rootMetaData, $metaXPath);

            if (($meta === null) || ($rootMetaData === '' && !$meta)) $meta = false;
        } else {
            $meta = get_post_meta($postId, $metaId, $single);
            // if ($postId == 442 && $metaId == 'nova_postmeta') {
            //     debug(get_post_meta(442, 'nova_postmeta'), true);
            //     d($meta);
            // } else {
            //     debug($postId);
            //     debug($metaId);
            // }
        }
        // debug($metaId);

        
        $_post = get_post($postId);
        $friendlyId = $this->friendlyOptionId($metaId);
        $postTypes = array($_post->post_type);
        if (in_array($_post->post_type, $this->Post->getPostTypes())) $postTypes[] = 'single';
        if (!$meta && isset($this->_schema[$friendlyId])) {
            $meta = $this->_schema[$friendlyId];
        } elseif ($friendlyId == 'postmeta') {
            foreach ($postTypes as $postType) {
                if (isset($this->_schema[$postType]) && isset($this->_schema[$postType]['postmeta'])) {
                    if (!$meta) {
                        $meta = $this->_schema[$postType]['postmeta'];
                    } else {
                        $meta = array_merge($this->_schema[$postType]['postmeta'], (array) $meta);
                    }
                    if (is_array($meta)) {
                        $meta = Set::booleanize($meta);
                    }
                }
            }
        }
        
        if ($meta && !is_admin() && function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            $meta = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($meta);
        }
        
        return $meta;
    }
    
    public function updatePostMetaValue($key, $val) {
        $posts = query_posts(array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        
        $metaKey = $this->gummOptionId('postmeta');
        foreach ($posts as $post) {
            $meta = (array) $post->PostMeta;
            
            if (isset($meta[$key])) {
                $meta[$key] = $val;
                update_post_meta($post->ID, $metaKey, $meta);
                
            }
        }
    }
}
?>