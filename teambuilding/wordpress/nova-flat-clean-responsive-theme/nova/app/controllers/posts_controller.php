<?php
class PostsController extends AppController {
    
    /**
     * @var array
     */
    public $uses = array('Post', 'Layout', 'PostMeta');
    
    public $currentPostTypeColumns;
    
    public $wpFilters = array(
        'post_class' => '_filterPostClass',
        'pre_post_link' => '_filterCategoryPermalink',
    );

    public function __construct() {
        parent::__construct();

        add_action('init', array(&$this, 'registerCustomPostTypes'), 10);
        add_action('save_post', array(&$this, 'admin_save'));
        
    }
    
    public function registerCustomPostTypes() {
        $customPostTypes = Configure::read('customPostTypes');
        
        if (!$customPostTypes) return;
        
        if (isset($customPostTypes['labels'])) $customPostTypes = array($customPostTypes);
        
        foreach ($customPostTypes as $postType => $typeArgs) {
            if (isset($typeArgs['args'])) {
                if(function_exists('register_post_type')) {
                    register_post_type($postType, $typeArgs['args']);
                }
            }

            if (isset($typeArgs['columns'])) {
                App::import('Component', 'PostColumn');
                $PostColumnComponent = new PostColumnComponent($typeArgs['columns']);
                
                add_filter('manage_' . $postType . '_posts_columns', array($PostColumnComponent, 'getColumns'));
                add_action('manage_' . $postType . '_posts_custom_column',  array($PostColumnComponent, 'getColumn'));
            }
        }
    }
    
    /**
     * @param int $postId
     * @return json|void
     */
    public function admin_check_format_availability($postId=null, $format=null) {
        $this->autoRender = false;
        
        if (!$postId) $postId = $this->RequestHandler->getNamed('postId');
        if (!$postId) return;
        if (!$format) $format = $this->RequestHandler->getNamed('postFormat');
        if (!$format) return;
        
        $post = $this->Post->findById($postId);
        
        $availability = array('ok' => true, 'msg' => '');
        switch ($format) {
         case 'gallery':
            if (count($post->Media) < 2) {
                $availability['ok'] = false;
                $availability['msg'] = __('You need to upload at least two media items for the gallery post format.', 'gummfw');
            }
            break;
         case 'video':
            if (!$this->Post->getVideoForPost($post)) {
                $availability['ok'] = false;
                $availability['msg'] = __('You need to upload/embed at least one video item for the video post format.', 'gummfw');
            }
        }
        
        if ($this->RequestHandler->isAjax()) {
            echo json_encode($availability);
        }
        
        return $availability;
    }

    public function admin_save($post_id) {
        if (!$this->data || !isset($this->data['GummPostMeta'])) return $post_id;
        // verify nonce
        // if (!isset($_POST[GUMM_THEME_PREFIX . '_meta_box_nonce'])) {
            // return $post_id;
        // }
        // if (!wp_verify_nonce($_POST[GUMM_THEME_PREFIX . '_meta_box_nonce'], GUMM_THEME_PREFIX . '-custom-post-meta-nonce')) {
            // return $post_id;
        // }

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        
        if (isset($this->data['GummPostMeta']) && is_array($this->data['GummPostMeta'])) {
            // if (isset($this->data['GummMedia']) && is_array($this->data['GummMedia'])) {
            //     $this->data['GummPostMeta'] = Set::merge($this->data['GummPostMeta'], $this->data['GummMedia']);
            // }
            // d($this->data);
            foreach ($this->data['GummPostMeta'] as $metaKey => $metaValue) {
                $old = $this->PostMeta->find($post_id, $metaKey);
                // $old = get_post_meta($post_id, $metaKey, true);
                $new = $metaValue;
                
                // debug($metaValue);
                
                if ($new && $new != $old) {
                    update_post_meta($post_id, $metaKey, $new);
                } elseif ('' == $new && $old) {
                    delete_post_meta($post_id, $metaKey, $old);
                }
            }
        }

        // Check for custom metaboxes configuration
        // $customMetaboxes = Configure::read('admin.metaboxes');
        // $postType = get_post_type($post_id);
        // if ($customMetaboxes) {
        //  foreach ($customMetaboxes as $customMetabox) {
        //      if ($customMetabox['page'] !== $postType) continue;
        //      foreach ($customMetabox['fields'] as $field) {
        //          $old = get_post_meta($post_id, $field['id'], true);
        //          $new = $_POST[$field['id']];
        // 
        //          if ($new && $new != $old) {
        //              update_post_meta($post_id, $field['id'], $new);
        //          } elseif ('' == $new && $old) {
        //              delete_post_meta($post_id, $field['id'], $old);
        //          }
        //      }
        //  }
        // }
    }
    
    public function index_related($post, $num=4) {
        $posts = $this->Post->findRelated($post, $num);

        $this->set(compact('post', 'posts'));
        
        if ($post->post_type !== 'post') {
            $this->render('index_related_' . $post->post_type);
        }
    }
    
    // =============== //
    // REQUEST ACTIONS //
    // =============== //
    
    public function search($s) {
        $posts = $this->Post->find('all', array(
            'conditions' => array(
                's' => $s
            ),
            'limit' => 20
        ));
        
        $posts = $this->Post->groupByPostType($posts);
        
        $this->set(compact('posts', 's'));
    }
    
    public function getPostTypes() {
        
    }
    
    // =========== //
    // WP FILTERS //
    // =========== //
    
    /**
     * @param array $class
     * @return array
     */
    public function _filterPostClass($class) {
        global $post;
        
        switch ($post->post_type) {
         case 'portfolio':
            $class[] = $this->Layout->findLayoutForPortfolioPost($post->ID);

            break;
        }
        
        return $class;
    }
    
    /**
     * @param string $permalink
     * @return string
     */
    public function _filterCategoryPermalink($permalink) {
        // d($permalink);
        // d(func_get_args());
        // $ps = get_option('permalink_structure');
        // d($ps);
        return $permalink;
        // debug($permalink);
    }
}
?>