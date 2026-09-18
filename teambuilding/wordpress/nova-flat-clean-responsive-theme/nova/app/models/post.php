<?php
class PostModel extends GummModel {
    
    /**
     * @var array
     */
    public $inRelation = array('Media', 'PostMeta', 'Option');
    
    private $_attachmentsModelsMap = array(
        'Media' => array('image/png', 'image/jpeg', 'image/gif')
    );
    
    private $_postTypesOptions = array();
    
    /**
     * @param string $type
     * @param array $conditions
     * @return array
     */
    public function find($type='all', $conditions=array()) {
        $conditions = array_merge(array(
            'conditions' => array(),
            'sort' => 'descending',
            'limit' => null,
        ), $conditions);
        extract($conditions, EXTR_OVERWRITE);
        
        $conditions['limit'] = $limit;
        
        $conditions = $this->_parseQueryConditions($conditions);
        
        // debug($conditions);
        
        $WP_Query = new WP_Query($conditions);
        $posts = $WP_Query->get_posts();
        wp_reset_query();  
        
        $result = array();
        switch ($type) {
         case 'first':
            $result = reset($posts);
            break;
         case 'list':
            foreach ($posts as $post) {
                $result[$post->ID] = $post->post_title;
            }
            break;
         default:
            $result = $posts;
        }
        
        return $result;
    }
    
    public function findById($id) {
        $post = get_post($id);

        $this->Media->setMediaFieldsByType($post);
        
        return $this->bindPostModels($post);
    }
    
    /**
     * @param object $post
     * @return object
     */
    public function getVideoForPost($post) {
        if (!isset($post->Media)) $post = $this->createAssociations($post);
        
        $videoItems = array();
        foreach ($post->Media as $mediaItem) {
            if (in_array($mediaItem->post_mime_type, $this->Media->getMediaMimeType('video'))) {
                $videoItems[] = $mediaItem;
            }
        }
        
        return $videoItems;
    }
    
    /**
     * Called from "the_posts" hook
     * Appends associated model/objects to the post object
     * 
     * @param array $posts
     * @return array
     */
    public function bindPostsModels($posts) {
        foreach ($posts as &$post) {
            $post = $this->bindPostModels($post);
        }
        
        return $posts;
    }
    
    /**
     * Called mainly from "add_meta_boxes" hook, as the "query_posts" hoook is not available
     * when editing single post
     * Appends associated model/objects to the post object
     * 
     * @param mixed $post
     * @return object
     */
    public function bindPostModels($post=null) {
        if (!$post || !is_object($post)) global $post;
        if (!$post) return $post;
        if ($post->post_type == 'atachment') return $post;
        
        foreach ($this->inRelation as $assoc) {
            $call = 'bindModel' . Inflector::camelize($assoc);
            call_user_func_array(array($this, $call), array(&$post));
        }
        
        return $post;
    }
    
    /**
     * @param object $post
     * @return object
     */
    public function bindModelMedia(&$post) {
        if (in_array($post->post_type, array('revision', 'attachment'))) return $post;
        
        $mediaMimeTypes = $this->Media->getMediaMimeTypes();
        
        if (!isset($post->Media)) $post->Media = array();
        if (!isset($post->Thumbnail)) $post->Thumbnail = false;
        
        $thumbnailId = get_post_thumbnail_id($post->ID);
        if ($thumbnailId) {
            $thumbnailPost = get_post($thumbnailId);
            if ($thumbnailPost) {
                $this->Media->setMediaFieldsByType($thumbnailPost);
                $post->Thumbnail = $thumbnailPost;
            }
        }
        
        $attachedPostsIds = $this->PostMeta->find($post->ID, '_gumm_attached_files');
        $attachedPostsIds = apply_filters('gumm_attached_media_ids', $attachedPostsIds, $post);
        
        if ($attachedPostsIds) {
            if ($attachments = $this->findAttachmentPosts($attachedPostsIds)) {
                foreach ($attachments as $attachment) {
                    if (in_array($attachment->post_mime_type, $mediaMimeTypes)) {
                        $this->Media->setMediaFieldsByType($attachment);
                        array_push($post->Media, $attachment);
                    }
                }
            }
            
        }
        
        if (!$post->Thumbnail && $post->Media) {
            $post->Thumbnail = reset($post->Media);
        }

        return $post;
    }
    
    /**
     * @param object $post
     * @return object
     */
    public function bindModelPostMeta(&$post) {
        if (is_a($post, 'WP_POST') && !isset($post->PostMeta)) {
            $postMeta = Set::booleanize((array) $this->PostMeta->find($post->ID, 'postmeta'));
        
            $post->PostMeta = $postMeta;
        }
        
        return $post;
    }
    
    /**
     * @param object $post
     * @return object
     */
    public function bindModelOption(&$post) {
        $options = $this->getThemeOptionForPost($post);
        
        $post->GummOption = $options;
    }
    
    public function findCategoriesForPostType($postType) {
        $termName = $this->getPostCategoryTermName($postType);
        $categories = get_terms($termName);
        
        $result = array();
        if (!isset($categories->error)) {
            foreach ($categories as $category) {
                $result[$category->term_id] = $category->slug;
            }
        }

        return $result;
    }
    
    public function findAttachmentPosts($id) {
        if (!$id) return array();
        
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'attachment',
        );
        if (is_array($id)) {
            $args['post__in'] = $id;
        } elseif (is_numeric($id)) {
            $args['p'] = $id;
        }
        
        $attachmentsUnordered = get_posts($args);
        $attachments = array();
        if (is_array($id)) {
            foreach ($id as $attachmentPostId) {
                foreach ($attachmentsUnordered as $k => $attachmentUnordered) {
                    if ($attachmentUnordered->ID == $attachmentPostId) {
                        $attachments[] = $attachmentUnordered;
                        unset($attachmentsUnordered[$k]);
                        break;
                    }
                }
            }
        } else {
            $attachments = $attachmentsUnordered;
        }
        
        return $attachments;
    }
    
    public function findRelated($post, $num) {
        $posts = array();
        if ($post) {
            $tagIds = wp_get_post_tags($post->ID, array('fields' => 'ids'));
            $categories = $this->getPostCategories($post);
            
            $args = array(
                'posts_per_page' => $num,
                'post__not_in' => array($post->ID),
                'post_type' => $post->post_type,
                'tax_query' => array(
                    'relation' => 'OR'
                ),
            );
            if ($tagIds || $categories) {
                if ($tagIds) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'post_tag',
                        'field' => 'id',
                        'terms' => $tagIds,
                    );
                }
                if ($categories) {
                    $args['tax_query'][] = array(
                        'taxonomy' => $this->getPostTypeCategoryTaxonomy($post->post_type),
                        'field' => 'id',
                        'terms' => array_keys($categories),
                    );
                }
                
                $posts = query_posts($args);
                wp_reset_query();
            }
        }
        
        return $posts;
    }
    
    public function getPostTypes() {
        return array_merge(array('post' => 'post'), get_post_types(array('capability_type' => 'post', '_builtin' => false)));
    }
    
    public function getPostCategories($post) {
        $categories = array();
        
        $termName = $this->getPostCategoryTermName($post->post_type);
        $terms = get_the_terms($post->ID, $termName);
        if ($terms && !is_a($terms, 'WP_Error')) {
            foreach ($terms as $term) {
                $categories[$term->term_id] = $term->name;
            }
        }
        return $categories;
    }
    
    public function getPostCategoryTermName($postType) {
        $termName = ($postType == 'post') ? 'category' : $postType . '_category';
        
        return apply_filters('gumm_post_cateory_term_name', $termName, $postType);
    }
    
    public function getPostCategoryLink($post, $categoryId) {
        $link = false;
        
        if ($post->post_type === 'post') {
            $link = get_category_link($categoryId);
        } else {
            $link = get_term_link($categoryId, $this->getPostCategoryTermName($post->post_type));
            if (is_wp_error($link)) {
                $link = false;
            }
        }
        return $link;
    }
    
    public function getPostTags($post) {
        return wp_get_post_tags($post->ID);
    }
    
    public function getPostTypeCategories($postType) {
        $termName = $this->getPostTypeCategoryTaxonomy($postType);
        
        $categories = array();
        if ($terms = get_terms($termName)) {
            if (!isset($terms->errors)) {
                foreach ($terms as $term) {
                    $categories[$term->term_id] = $term->name;
                }
            }
        }
        
        return $categories;
    }
    
    public function getPostTypeCategoryTaxonomy($postType) {
        return $postType == 'post' ? 'category' : $postType . '_category';
    }
    
    public function groupByPostType(array $posts, array $settings=array()) {
        $settings = array_merge(array(
            'bestMatch' => false,
        ), $settings);
        
        $result = array();
        if ($posts) {
            if ($settings['bestMatch']) $result['bestMatch'] = array($posts[0]);
            foreach ($posts as $post) {
                if (!isset($result[$post->post_type])) $result[$post->post_type] = array();
                
                $result[$post->post_type][] = $post;
            }
        }
        
        return $result;
    }
    
    public function getThemeOptionForPost($post) {
        $postType =  ($post->post_type === 'post') ? 'blog' : $post->post_type;
        if (!isset($this->_postTypesOptions[$postType])) {
            $options = $this->Option->find($postType);
            if (!$options) $options = array();
            
            $optionId = $this->gummOptionId($postType);
            $configOptions = $this->Option->getConfigOption($optionId);
            if ($configOptions && is_array($configOptions) && isset($configOptions['default'])) {
                $options = Set::merge($configOptions['default'], $options);
                $options = Set::booleanize($options);
            }
            
            $this->_postTypesOptions[$postType] = $options;
        }
        
        return $this->_postTypesOptions[$postType];
    }
    
    public function getQueriedObject() {
        $post = get_queried_object();
        $this->bindModelPostMeta($post);
        
        return $post;
    }
}
?>