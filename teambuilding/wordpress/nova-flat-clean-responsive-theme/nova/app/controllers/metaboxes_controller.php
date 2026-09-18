<?php
class MetaboxesController extends AppController {
    
    public $uses = array('PostMeta', 'Post');
    
    private $metaboxes;
    
    private $workingMetabox;
    
    private $hiddenMetaboxes = array();
    
    public function __construct() {
        parent::__construct();
    }
    
    public function registerMetaboxes() {
        $this->metaboxes = Configure::read('admin.metaboxes');
        add_action('admin_menu', array(&$this, 'initializeMetaboxes'));
        add_filter('hidden_meta_boxes', array(&$this, 'filterHiddenMetaboxes'), 10, 3);
    }
    
    public function setWorkingMetabox($metabox) {
        $this->workingMetabox =& $metabox;
    }
    
    public function initializeMetaboxes() {
        foreach ($this->metaboxes as $metabox) {
            $callback = (isset($metabox['requestAction'])) ? $metabox['requestAction'] : array('controller' => 'metaboxes', 'action' => 'edit');
            $callback = GummDispatcher::getCallbackToDispatch($callback);

            if (!$callback) trigger_error(__('Invalid callback to dispatch for metabox', 'gummfw'));

            $metabox['page'] = (array) $metabox['page'];
            
            $pages = array();
            foreach ($metabox['page'] as $k => $page) {
                $type = $page;
                $not = array();
                if (is_array($page)) {
                    $type = $k;
                    if (isset($page['NOT'])) $not = (array) $page['NOT'];
                }
                if ($type === 'single') {
                    $postTypes = $this->Post->getPostTypes();
                    foreach ($postTypes as $postType) {
                        if (!in_array($postType, $not)) {
                            $this->addMetaBox($metabox, $callback, $postType);
                            $pages[] = $postType;
                        }
                    }
                } else {
                    $shouldAdd = true;
                    if (isset($_REQUEST['post']) && in_array($_REQUEST['post'], $not)) {
                        $shouldAdd = false;
                    }
                    if ($shouldAdd) {
                        $this->addMetaBox($metabox, $callback, $type);
                        $pages[] = $type;
                    }
                        
                }

            }
            if (isset($metabox['hidden']) && $metabox['hidden']) {
                $hideOn = $pages;
                if ($metabox['hidden'] !== true) {
                    $hideOn = array();
                    $metabox['hidden'] = (array) $metabox['hidden'];
                    foreach ($metabox['hidden'] as $hideOnType) {
                        if ($hideOnType === 'single') {
                            $hideOn = array_merge($hideOn, $this->Post->getPostTypes());
                        } else {
                            $hideOn[] = $hideOnType;
                        }
                    }
                }
                foreach ($hideOn as $page) {
                    if (!isset($this->hiddenMetaboxes[$page])) $this->hiddenMetaboxes[$page] = array();
                    $this->hiddenMetaboxes[$page][] = $metabox['id'];
                }
            }
            
        }
    }
    
    private function addMetaBox($metabox, $callback, $page) {
        add_meta_box($metabox['id'], $metabox['title'], $callback, $page, $metabox['context'], $metabox['priority'], $metabox);
    }
    
    public function filterHiddenMetaboxes($hidden, $screen, $useDefaults ) {
        if ( isset($this->hiddenMetaboxes[$screen->post_type]) && $this->hiddenMetaboxes[$screen->post_type] && $useDefaults ) {
            $hidden = array_merge($hidden, $this->hiddenMetaboxes[$screen->post_type]);
        }
        
        return $hidden;

    }
    
    /**
     * 
     */
    public function admin_edit($post, $args) {
        $metabox = $args['args'];
        $this->set(compact('metabox'));
        
        $this->render(__FUNCTION__);
    }
    
    // public function admin_post_format($post, $args) {
    //     $metabox = $args['args'];
    //     $this->set(compact('post', 'metabox'));
    //     
    //     $this->render(__FUNCTION__);
    // }
    
    public function admin_testimonial_author($post, $args) {
        $metabox = $args['args'];
        $this->set(compact('post', 'metabox'));
        
        $this->render(__FUNCTION__);
    }
    
    public function admin_post_media($post, $args) {
        $metabox = $args['args'];
        $this->set(compact('post', 'metabox'));
        
        $this->render(__FUNCTION__);
    }
    
    public function admin_post_loop_dimensions($post, $args) {
        $metabox = $args['args'];
        $dimensions = $this->PostMeta->find($post->ID, $metabox['id']);
        
        $this->set(compact('post', 'metabox', 'dimensions'));
        
        $this->render(__FUNCTION__);
    }
    
    public function admin_layouts_page_builder($post, $args) {
        $this->autoRender = false;
        $metabox = $args['args'];
        
        $this->requestAction(array('controller' => 'layouts', 'action' => 'admin_template_builder', $post, $metabox['id']));
    }
    
    public function admin_layouts_schema($post, $args) {
        $metabox = $args['args'];

        $this->set(compact('post', 'metabox'));

        $this->render(__FUNCTION__);
    }
    
    public function admin_page_map($post, $args) {
        $metabox = $args['args'];
        
        $this->set(compact('post', 'metabox'));

        $this->render(__FUNCTION__);
    }
    
    public function admin_edit_background($post, $args) {
        $metabox = $args['args'];
        
        $metabox = array_merge(array(
            'inputStyleSelector' => 'body',
            'contentSelector' => true,
        ), $metabox);

        $this->set(compact('post', 'metabox'));

        $this->render(__FUNCTION__);
    }
}
?>