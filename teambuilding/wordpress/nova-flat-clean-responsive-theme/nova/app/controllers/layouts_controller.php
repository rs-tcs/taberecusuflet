<?php
class LayoutsController extends AppController {
    
    /**
     * @var arary
     */
    public $uses = array('LayoutBlock', 'Option', 'Sidebar', 'Layout', 'PostMeta', 'BackgroundPattern');
    
    /**
     * @var array
     */
    public $components = array(
        'RequestHandler',
        'Cookie',
        'Fonts',
    );
    
    
    public function admin_test_edit($optionId) {
        $this->set(compact('optionId'));
    }
    
    /**
     * @param object $post
     * @param string $metaId
     */
    public function admin_template_builder($post, $templateId, $model='GummPostMeta', $schemaInputsContainerSelector='page-schema-metabox', $mode='full') {
        if ($post) $templateId = 'post-' . $post->post_type . '-' . $post->ID; 
        
        App::import('Component', 'GummTemplateBuilder');
        $GummTemplateBuilder = new GummTemplateBuilderComponent($post, $templateId, $model);
        
        $elementsEnabled = $GummTemplateBuilder->getTemplateElementsEnabled();
        
        foreach ($elementsEnabled as $position => &$enabledList) {
            foreach ($enabledList as &$elementEnabled) {
                $elementEnabled->model($model);
            }
        }
        
        $elementsAvailable = $GummTemplateBuilder->getTemplateElementsAvailable('group');

        foreach ($elementsAvailable as $position => &$availableList) {
            foreach ($availableList as &$elementAvailable) {
                $elementAvailable->model($model);
            }
        }
        
        $sidebars = $this->Sidebar->find('all', array('conditions' => array('id !=' => array('gumm-footer-sidebar-1', 'gumm-footer-sidebar-2', 'gumm-footer-sidebar-3', 'gumm-footer-sidebar-4'))));

        $layoutSidebars = $this->Layout->findSidebarsForLayout($templateId);

        $layoutSchema = $this->Layout->findSchemaStringForOption(str_replace('.sidebars', '.schema', $templateId));
        
        $this->set(compact('templateId', 'sidebars', 'layoutSidebars', 'layoutSchema'));
        
        $this->set(compact('elementsEnabled', 'elementsAvailable', 'post', 'model', 'schemaInputsContainerSelector', 'mode'));
    }
    
    /**
     * @param string $elementId
     * @param int $postId
     * @return void
     */
    public function admin_view_layout_element($elementId, $postId) {
        $this->set(compact('elementId', 'postId'));
    }
    
    /**
     * @param string $elementId
     * @param int $postId
     * @return void
     */
    public function admin_add_layout_element($elementId=null, $postId=null, $model='GummPostMeta', $metaKey=null) {
        if (!$elementId) $elementId = $this->RequestHandler->getNamed('elementId');
        if (!$postId) $postId = $this->RequestHandler->getNamed('postId');
        if ($this->RequestHandler->getNamed('model')) {
            $model = $this->RequestHandler->getNamed('model');
        }
        if (!$metaKey) {
            $metaKey = $this->RequestHandler->getNamed('metaKey');
        }
        
        $element = null;
        
        if ($elementId) {
            // $post = null;
            // if ($postId) {
                // $post = get_post($postId);
            // }
            App::import('Component', 'GummTemplateBuilder');
            $GummTemplateBuilder = new GummTemplateBuilderComponent();

            $elementsAvailable = Set::flatten($GummTemplateBuilder->getTemplateElementsAvailable());
            
            foreach ($elementsAvailable as $elementAvailable) {
                if ($elementAvailable->id() == $elementId) {
                    $element = $elementAvailable;
                    $element->id(uniqid());
                    $element->model($model);
                    if ($metaKey) {
                        $element->metaKey = $metaKey;
                    }
                    if ($layoutPosition = $this->RequestHandler->getNamed('layoutPosition')) {
                        $element->setLayoutPosition($layoutPosition);
                    }
                    break;
                }
            }

        }
        
        $this->set(compact('element'));

    }
    
    /**
     * @param string $elementName
     * @param string $elementId
     * @param int $postId
     * @param string $model
     * @return void
     */
    public function admin_edit_layout_element($elementName=null, $elementId=null, $postId=null, $model='Option') {
        if (!$elementName) $elementName = $this->RequestHandler->getNamed('elementName');
        if (!$elementId) $elementId = $this->RequestHandler->getNamed('elementId');
        if (!$postId) $postId = $this->RequestHandler->getNamed('postId');
        if (!$model || $this->RequestHandler->getNamed('model')) $model = $this->RequestHandler->getNamed('model');
        
        $post = ($postId) ? get_post($postId) : null;
        
        App::import('Component', 'GummTemplateBuilder');
        $GummTemplateBuilder = new GummTemplateBuilderComponent($post);
        
        $elementsAvailable = $GummTemplateBuilder->getTemplateElementsAvailable();
        $elementsEnabled = $GummTemplateBuilder->getTemplateElementsEnabled();
        
        debug($elementId);
        d($elementsEnabled);
        
        $element = null;
        if (isset($elementsAvailable[$elementName])) {
            $element = $elementsAvailable[$elementName];
            
            if ($elementId) $element->id($elementId);
            if ($model) $element->model($model);
        }
        
        $this->set(compact('element'));
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_edit_layer_fonts($optionId) {
        $fontSettings = array_merge(array(
            'font-family' => 'Helvetica Neue,Helvetica,Arial,sans-serif',
            'color' => '',
            'font-weight' => 'normal',
            'font-size' => 12,
        ), (array) $this->Option->find($optionId));
        
        $fontSettings['vendor'] = $this->Fonts->getFontsVendor($fontSettings['font-family']);
        
        $this->set(compact('optionId', 'fontSettings'));
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_fonts_index($optionId) {
        $this->set(compact('optionId'));
    }

    /**
     * @return void
     */
    public function admin_index_background_patterns() {
        $backgroundPatterns = $this->BackgroundPattern->find('all');
        
        
        $backgroundPatternTypes = array();
        foreach ($backgroundPatterns as $pattern) {
            $type = $pattern['type'];
            if (isset($backgroundPatternTypes[$type])) continue;
            
            $backgroundPatternTypes[$type] = array(
                'name' => $type,
                'types' => true,
                'icon' => $pattern['url'],
            );
        }
        
        $this->set(compact('backgroundPatternTypes'));
    }
    
    /**
     * @param string $backgroundPatternType
     * @return void
     */
    public function admin_view_background_pattern($backgroundPatternType=null) {
        if (!$backgroundPatternType) $backgroundPatternType = $this->RequestHandler->getNamed('backgroundPatternType');
        $backgroundPatterns = $this->BackgroundPattern->find('first', array('conditions' => array('type' => $backgroundPatternType)));
        
        foreach ($backgroundPatterns as &$pattern) {
            $pattern['icon'] = $pattern['url'];
        }
        
        $this->set(compact('backgroundPatterns', 'backgroundPatternType'));
    }
    
    /**
     * @param string $patternUrl
     * @return void
     */
    public function admin_edit_background_pattern_weight($patternUrl=null) {
        if (!$patternUrl) $patternUrl = $this->RequestHandler->getNamed('patternUrl');
        
        $patternData = $this->BackgroundPattern->getPatternDataFromUrl($patternUrl);
        
        $this->set(compact('patternData'));
    }
    
    /**
     * @param string $optionId
     * @param string $model
     * @return void
     */
    public function admin_edit($optionId, $model='Option', $context='span2') {
        $currentSchema = $this->Layout->findSchemaStringForOption($optionId);
        $currentSchemaValue = $this->Layout->findSchemaStringForOption($optionId, false);

        $this->set(compact('optionId', 'currentSchema', 'currentSchemaValue', 'model', 'context'));
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_edit_layer_background_image($optionId='', $layerId=0, $model='Option') {
        if (!$optionId) $optionId = $this->RequestHandler->getNamed('optionId');
        if (!$layerId) $layerId = $this->RequestHandler->getNamed('layerId');
        if ($this->RequestHandler->getNamed('modelName')) $model = $this->RequestHandler->getNamed('modelName');
        
        $this->set(compact('optionId', 'layerId', 'model'));
    }
    
    /**
     * @param int $optionId
     * @return void
     */
    public function admin_edit_block_background_images($optionId, $model='Option') {
        $this->set(compact('optionId', 'model'));
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_edit_block_background_colors($optionId, $model='Option') {
        $this->set(compact('optionId', 'model'));
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_edit_block_colors($optionId) {
        $option = $this->Option->getConfigOption($optionId);
        
        $this->set(compact('option'));
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_edit_layout_sidebars($optionId) {
        $sidebars = $this->Sidebar->find('all', array(
            'conditions' => array(
                'id !=' => array(
                    'gumm-footer-sidebar-1',
                    'gumm-footer-sidebar-2',
                    'gumm-footer-sidebar-3',
                    'gumm-footer-sidebar-4'
                ),
            ),
        ));
        
        $layoutPage = $optionId;
        $optionParts = explode('.', $optionId);
        if (count($optionParts) > 1) $layoutPage = $optionParts[1];

        $layoutSidebars = $this->Layout->findSidebarsForLayout($layoutPage);

        $layoutSchema = $this->Layout->findSchemaStringForOption(str_replace('.sidebars', '.schema', $optionId));
        
        $option = $this->Option->getConfigOption($optionId);
        
        $this->set(compact('optionId', 'sidebars', 'layoutSidebars', 'layoutSchema', 'option'));
    }
    
    public function admin_store_user_navigation() {
        $this->autoRender = false;
        
        $tabId = trim($this->RequestHandler->getNamed('tabId'));
        $toolbarTabId = trim($this->RequestHandler->getNamed('toolbarTabId'));
        $optionId = trim($this->RequestHandler->getNamed('optionId'));
        $optionNavStatus = trim($this->RequestHandler->getNamed('optionNavStatus'));
        $pageBuilderActiveTab = $this->RequestHandler->getNamed('pageBuilderActiveTab');
        
        if ($tabId) {
            $this->Cookie->write('admin.selectedTabId', $tabId);
        }
        if ($toolbarTabId) {
            $this->Cookie->write('admin.selectedToolbarTabId', $toolbarTabId);
        }
        if ($optionId && $optionNavStatus) {
            $this->Cookie->write('admin.options.' . $optionId . '.status', $optionNavStatus);
        }
        if ($pageBuilderActiveTab !== false) {
            $this->Cookie->write('admin.pageBuilderActiveTab', (int) $pageBuilderActiveTab);
        }
    }
    
    public function admin_import_sample_data() {
        if ($action = $this->RequestHandler->getNamed('takeAction')) {
            switch ($action) {
             case 'import':
                GummRegistry::get('Component', 'Importer')->importSampleContent();
                break;
             case 'delete':
                GummRegistry::get('Component', 'Importer')->deleteSampleContent();
                break;
            }
        }
    }
    
    public function admin_import_sample_page() {
        $pages = GummRegistry::get('Component', 'Importer')->getSamplePages();
        $formError = false;
        $successMessage = false;
        $id = false;
        if ($this->data && isset($this->data['urlToImport']) && $this->data['urlToImport']) {
            $url = $this->data['urlToImport'];
            
            $query = array(
                'gummcontroller' => 'layouts',
                'action' => 'get_sample_page_data',
            );
            $query = http_build_query($query, 'PHP_QUERY_RFC1738');
            
            $rpcUrl = (strpos($url, '?') === false) ? $url . '?' . $query : $url . '&' . $query;

            $_pages = false;
            $rpcResult = false;
            
            $res = wp_remote_get($rpcUrl, array(
                'timeout' => 120
            ));
            if (is_wp_error($res)) {
                $formError = __('Errors: ', 'gummfw') . ' ' . implode('. ', Set::flatten($res->errors));
                $formError .= '. ' . __('Please check with your hosting provider if your server can connect to the specified URL.', 'gummfw');
            } else {
                if ($res['response']['code'] === 200) {
                    $rpcResult = json_decode($res['body'], true);
                    if ($rpcResult && isset($rpcResult['name'])) {
                        $_pages = GummRegistry::get('Component', 'Importer')->importSamplePage($rpcResult);
                    }

                    if ($_pages) {
                        $pages = $_pages;
                        $successMessage = __('Page imported successfully.', 'gummfw');
                    } else {
                        $formError = __('There was an error importing the page. Please make sure it is part of the demo site and is not a single post page.', 'gummfw');
                    }
                } else {
                    $formError = __('Page to import not found. Error code 404.', 'gummfw');
                }
            }

        } elseif ($this->data && isset($this->data['takeAction']) && $this->data['takeAction'] === 'removeAll') {
            if (GummRegistry::get('Component', 'Importer')->deleteSamplePages()) {
                $successMessage = __('Pages successfully deleted.', 'gummfw');
                $pages = array();
            } else {
                $formError = __('Could not delete pages. Please try again.', 'gummfw');
            }
        } elseif ($this->data) {
            $formError = __('Invalid url', 'gummfw');
        }
        
        $this->set(compact('formError', 'successMessage', 'pages'));
    }
    
    public function get_sample_page_data() {
        global $post;
        $result = false;
        if ($post && is_a($post, 'WP_Post') && $post->post_type === 'page') {
            $result = array(
                'name' => $post->post_title,
                'postType' => 'page',
                'postContent' => $post->post_content,
                'postExcerpt' => $post->post_excerpt,
                'meta' => array(
                    'layout_components' => $this->PostMeta->find($post->ID, 'layout_components'),
                    'postmeta' => $post->PostMeta,
                ),
            );
        }
        echo json_encode($result);
        die;
    }
    
    // ========== //
    // WP HOOKS //
    // ========== //
    
    
    /**
     * DEPRICATED
     * 
     * @return void
     */
    public function admin_edit_layer_backgrounds() {
        $optionId = $this->RequestHandler->getNamed('optionId');
        $layerId = $this->RequestHandler->getNamed('layerId');
        
        $blockData = $this->Option->find($optionId);
        // if (!$blockData) $this->LayoutBlock->add();
        
        if (!$layerId) $layerId = $this->getNewBlockLayerId($optionId);

        $this->set(compact('optionId', 'layerId'));
        $this->render();
        
        if ($this->RequestHandler->isAjax()) {
            die();
        }
    }
    
    public function getNewBlockLayerId($optionId) {
        return $this->LayoutBlock->getNewBlockLayerId($optionId);
    }
}