<?php
class LayoutHelper extends GummHelper {
    
    /**
     * @var array
     */
    public $helpers = array(
        'Form',
        'Wp',
        'Media',
        'Html',
    );
    
    /**
     * @var array
     */
    public $wpActions = array(
        'admin_print_footer_scripts' => array(
            '_actionPrintColorPickerJs',
            '_actionPrintGummSwitchJs',
        ),
    );
    
    /**
     * @var array
     */
    public $wpFilters = array(
        'post_class' => '_filterPostClass',
        'body_class' => '_filterBodyClass',
    );
    
    /**
     * @var array
     */
    public $colorpickersSettings = array();
    
    /**
     * @var array
     */
    public $gummSwitchJsSettings = array();
    
    /**
     * @var string
     */
    private $_layerIdSchema = 'layer-%d';
    
    /**
     * @var int
     */
    private $_layerBlockDepth = 7;
    
    /**
     * @var int
     */
    private $_layerStartPosition = 2;
    
    /**
     * @var int
     */
    private $_blockData = array();
    
    /**
     * @var array
     */
    private $_defaultSidebars = array(
        'left' => 'Main Left Sidebar',
        'right' => 'Main Right Sidebar',
    );
    
    /**
     * @var array
     */
    private $_sidebarOrientations = array('left', 'right');
    
    /**
     * @var array
     */
    private $adminFieldsToEdit = array(
        'background_image' => '',
        'color' => '',
        'background_color' => '',
        'background_position' => '',
        'background_repeat' => '',
    );

    /**
     * @var array
     */
    public $adminToolbarSettings = array(
        'home' => array(
            'class' => 'icon-home',
        ),
        'color' => array(
            'class' => 'icon-beaker',
        ),
        'background' => array(
            'class' => 'icon-picture',
        ),
        'typography' => array(
            'class' => 'icon-font',
        ),
        'layout' => array(
            'class' => 'icon-th',
        ),
        'single_layout' => array(
            'class' => 'icon-th-large',
        ),
        'effects' => array(
            'class' => 'icon-magic'
        ),
    );
    
    public function __construct() {
        parent::__construct();
        
        // Set translatable titles for the tab options
        foreach ($this->adminToolbarSettings as $tab => &$tabData) {
            if (isset($tabData['title'])) continue;
            switch ($tab) {
             case 'home':
                $tabData['title'] = __('Home', 'gummfw');
                break;
             case 'typography':
                $tabData['title'] = __('Typography', 'gummfw');
                break;
             case 'background':
                $tabData['title'] = __('Backgrounds', 'gummfw');
                break;
             case 'color':
                $tabData['title'] = __('Colors', 'gummfw');
                break;
             case 'layout':
                $tabData['title'] = __('Layout', 'gummfw');
                break;
             case 'single_layout':
                $tabData['title'] = __('Single Layout', 'gummfw'); 
                break;
             case 'effects':
                $tabData['title'] = __('Effect', 'gummfw');
                break;
            }
        }
        
    }
    
    // ======= //
    // EDITORS //
    // ======= //
    
    /**
     * @param int $optionId
     * @param int $layerId
     * @return string
     */
    public function blockBackgroundImagesEditor($optionId, $model='Option') {
        $outputHtml = '';
        
        $blockData = false;
        if ($model === 'GummPostMeta') {
            global $post;
            if ($post) {
                $blockData = GummRegistry::get('Model', 'PostMeta')->find($post->ID, $optionId);
            }
        } else {
            $blockData = $this->Wp->getOption($optionId);
        }
        
        if ($blockData) {
            $counter = 0;
            foreach ($blockData as $layerId => $imageData) {
                if (!$imageData['background-image']) continue;
                $outputHtml .= $this->layerBackgroundImagesEditor($optionId, $counter, $imageData, $model);
                $counter++;
            }
            
        }
        
        if (!$outputHtml) {
            $outputHtml .= $this->layerBackgroundImagesEditor($optionId, 0, array(), $model);
        }
        
        return $outputHtml;
    }
    
    /**
     * @param int $optionId
     * @param int $layerNumber
     * @return string
     */
    public function layerBackgroundImagesEditor($optionId, $layerId=0, $layerData=array(), $model='Option') {
        $layerData = array_merge(array(
            'background-image' => '',
            'background-repeat' => 'repeat',
            'background-position-left' => 'left',
            'background-position-top' => 'top',
        ), $layerData);

        $layerNum = $layerId + 1;
        
        $optionId .= '.image-' . $layerId;
        
        $isAjax = GummRegistry::get('Component', 'RequestHandler')->isAjax();
        
        ob_start();
?>
        <?php if (!$isAjax): ?>
        <div class="image-upload-container layout-layer-editor admin-fieldset" data-layer-id="<?php echo $layerId; ?>" style="margin-bottom:20px;">
        <?php endif; ?>
            <a href="#" class="admin-close-button close-button">×</a>
            <div class="row-fluid">
                <div class="span4">
                    <?php
                    $id = uniqid();
                    
                    echo $this->Media->singleUploadButton(array(
                        'button' => '<button id="' . $id . '" type="button" class="btn btn-success btn-admin-right">' . __('Upload', 'gummfw') . '</button>',
                        'scriptData' => array(
                            'gummcontroller' => 'media',
                            'action' => 'gumm_upload',
                            'gummadmin' => true,
                            'ajax' => true,
                            '_render' => '0',
                        ),
                        'callbacks' => array('onComplete' => 'layoutLayerEditor_onAjaxUploadComplete'),
                    ), array('id' => $id));

                    echo $this->Html->link(__('Browse', 'gummfw'), array(
                        'controller' => 'layouts',
                        'action' => 'index_background_patterns',
                        'admin' => true,
                        'ajax' => true,
                    ), array(
                        'class' => 'btn browse-background',
                    ));
                    
                    echo '<div class="input-wrap wrap-text input-prepend" style="margin-top:20px;">';
                    echo $this->Form->input(
                        '',
                        array(
                            'id' => $model . '.' . $optionId . '.background-image',
                            'type' => 'text',
                            'name' => __('URL', 'gummfw'),
                        ),
                        array(
                            'value' => $layerData['background-image'],
                            'class' => 'span12 single-upload-value',
                        ), array(
                            'div' => false,
                            'prepend' => true,
                        )
                    );
                    echo '</div>'
                    ?>
                </div>
                <div class="span4">
                    <?php
                    echo $this->Form->input(
                        '',
                        array(
                            'id' => $model . '.' . $optionId . '.background-repeat',
                            'type' => 'select',
                            'inputOptions' => array(
                                'repeat' => __('repeat both horizontally and vertically', 'gummfw'),
                                'repeat-x' => __('repeat horizontally', 'gummfw'),
                                'repeat-y' => __('repeat vertically', 'gummfw'),
                                'no-repeat' => __('no repeat', 'gummfw'),
                            )
                        ), array(
                            'value' => $layerData['background-repeat'],
                            'class' => 'one-third-select layout-layer-editor-bg-repeat'
                        ), array(
                            'div' => 'prop-input',
                            'label' => __('Image Properties:', 'gummfw'),
                        )
                    );

                    echo $this->Form->input(
                        '',
                        array(
                            'id' => $model . '.' . $optionId . '.background-position-left',
                            'type' => 'select',
                            'inputOptions' => array(
                                'left' => __('left', 'gummfw'),
                                'center' => __('center', 'gummfw'),
                                'right' => __('right', 'gummfw'),
                            )
                        ), array(
                            'value' => $layerData['background-position-left'],
                            'class' => 'one-third-select layout-layer-editor-bg-position-left'
                        ), array(
                            'div' => 'prop-input',
                            'label' => __('Horizontal:', 'gummfw'),
                        )
                    );
                    echo $this->Form->input(
                        '',
                        array(
                            'id' => $model . '.' . $optionId . '.background-position-top',
                            'type' => 'select',
                            'inputOptions' => array(
                                'top' => __('top', 'gummfw'),
                                'center' => __('center', 'gummfw'),
                                'bottom' => __('bottom', 'gummfw'),
                            )
                        ), array(
                            'value' => $layerData['background-position-top'],
                            'class' => 'one-third-select layout-layer-editor-bg-position-left',
                        ), array(
                            'div' => 'prop-input',
                            'label' => __('Vertical:', 'gummfw'),
                        )
                    );
                    ?>
                </div>
                <div class="span4">
                    <div class="background-image-preview">
                        <?php
                        $bgImageStyle = '';
                        if ($layerData['background-image']) {
                            $bgImageStyle = "background-image:url('{$layerData['background-image']}');";
                        }
                        ?>
                        <div style="<?php echo $bgImageStyle; ?> background-repeat:repeat; position:absolute; width:100%; height:100%; top:0; left:0;"></div>
                        <div class="layout-editor-pattern-weight-slider">
                        <?php
                            if ($layerData['background-image']) {
                                echo $this->requestAction(array('controller' => 'layouts', 'action' => 'admin_edit_background_pattern_weight', $layerData['background-image']));
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <span class="after-detail">
                <?php echo __('Image', 'gummfw'); ?> <span class="layer-editor-layer-num"><?php echo $layerNum; ?></span>
            </span>
        <?php if (!$isAjax): ?>
        </div>
        <?php endif; ?>
  
<?php      
        $output = ob_get_clean();
        
        return $output;
    }
    
    /**
     * @param int $optionId
     * @return string
     */
    public function blockBackgroundColorsEditor($optionId, $model='Option') {
        $color = $this->_getBlockData($optionId, $model);
        return $this->colorpicker(array(
            'class' => 'image-preview-container bgcolor-preview-container',
            'input' => array(
                'id' => $model . '.' . $optionId,
                'type' => 'hidden',
            ),
            'inputAttributes' => array(
                'value' => $color,
            ),
            'color' => $color,
            'clearButton' => true,
        ));
    }
    
    /**
     * @param array $pattern
     * @return string
     */
    public function blockBackgroundPatternEditor($pattern) {
        $defaultPatternUrl = $pattern['url'];
        $defaultTransparencyWeight = 100;
        
        ob_start();
?>
        <div class="block-background-patterns-editor">

            <?php if (isset($pattern['schemes']['light']) && isset($pattern['schemes']['dark'])): ?>
                <?php $this->gummSwitchJsSettings['background-scheme-switch'] = true; ?>
                
                <div class="ui-bgbuild-left">
                    <div class="gumm-switch background-scheme-switch"></div>
                
                    <div class="background-pattern-schemes">
                        <?php foreach ($pattern['schemes'] as $schemeName => $scheme): ?>
                        <?php if ($schemeName == 'light') $defaultPatternUrl = $scheme['weights'][$defaultTransparencyWeight]['url']; ?>
                        <div class="background-pattern-scheme" style="<?php echo ($schemeName == 'dark') ? 'display:none;' : '';?>">
                            <?php
                                $radioInputOptions = array();
                                foreach ($scheme['weights'] as $weight) {
                                    $radioInputOptions[$weight['value']] = $weight['url'];
                                }
                                echo $this->Form->input('', array(
                                    'id' => 'background-pattern-weight-' . uniqid(),
                                    'type' => 'number',
                                    'inputOptions' => $radioInputOptions,
                                ), array(
                                    'value' => $defaultTransparencyWeight,
                                ), array(
                                    'slider' => array(
                                        'min' => 25,
                                        'step' => 25,
                                        'numberType' => '%'
                                    ),
                                ));
                            ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
            <?php endif; ?>
            
            <input type="hidden" value="<?php echo $defaultPatternUrl; ?>" name="" class="block-background-pattern-url" />
            <div style="width: 50px; height: 50px; position: absolute; right: 20px; top: 50px;" class="block-background-pattern-preview image-preview-container">
                <div style="width: 100%; height:100%; background-image: url('<?php echo $defaultPatternUrl; ?>')"></div>
            </div>
        
        </div>

<?php

        return ob_get_clean();
    }
    
    /**
     * @param string $optionId
     * @return array
     */
    private function _getLayerData($optionId) {
        $data = (array) $this->Wp->getOption($optionId);
        if (isset($data['background-position'])) {
            $bgPos = explode(' ', $data['background-position']);
            $data['background-position-left'] = reset($bgPos);
            $data['background-position-top'] = end($bgPos);
            unset($data['background-position']);
        }

        return $data;
    }
    
    /**
     * @param string $optionId
     * @return array
     */
    private function _getBlockData($optionId, $model='Option') {

        // d($this->Wp->getOption($optionId));
        if (!isset($this->_blockData[$optionId])) {
            if ($model === 'GummPostMeta') {
                global $post;
                if ($post) {
                    $this->_blockData[$optionId] = GummRegistry::get('Model', 'PostMeta')->find($post->ID, $optionId);                    
                } else {
                    $this->_blockData[$optionId] = '';
                }
            } else {
                $this->_blockData[$optionId] = $this->Wp->getOption($optionId);
            }
        }
        
        return $this->_blockData[$optionId];
    }
    
    /**
     * @param string $optionId
     * @param int $num
     * @return string
     */
    public function getLayerIdForOption($optionId, $num=1) {
        if (!strpos($optionId, '.')) return $optionId . '-' . $num;
        
        $parts = explode('.', $optionId);
        end($parts);
        
        return prev($parts) . '-' . $num;
    }
    
    // =================== //
    // LAYOUT INFO GETTERS //
    // =================== //
    
    public function getWrapWidth($asCss=true) {
        if (!$result = $this->Wp->getOption('layout_wrap_width')) {
            $result = false;
        } else {
            if ($asCss) {
                $result = $result == 'full' ? '100%' : $result . 'px';
            } else {
                $clientResolution = $this->Media->getClientResolution();
                if ($result == 'full' || $clientResolution < $result) {
                    $result = $clientResolution;
                }
            }
        }
        
        return $result;
    }
    
    public function getContentWidth($asCss=true) {
        if (!$result = $this->Wp->getOption('layout_content_width')) {
            $result = false;
        } else {
            if ($asCss) {
                $result = $result == 'full' ? '100%' : $result . 'px';
            } else {
                $result = $result == 'full' ? $this->getWrapWidth(false) : $result;
            }
        }
        
        return $result;
    }

    /**
     * @return string
     */
    public function getDefaultBlockLayerId($optionId) {
        $defaultBlockLayerId = sprintf($this->_layerIdSchema, $this->_layerBlockDepth);
        
        $blockData = $this->_getBlockData($optionId);
        for ($i=7; $i>=1; $i--) {
            $currLayerId = sprintf($this->_layerIdSchema, $i);
            if (!isset($blockData[$currLayerId])) {
                $defaultBlockLayerId = $currLayerId;
                break;
            }
        }

        return $defaultBlockLayerId;
    }
    
    /**
     * @param string $page
     * @param string $orientation
     * @return void
     */
    public function getSidebarForPage($orientation) {
        $LayoutModel = GummRegistry::get('Model', 'Layout');

        $sidebars = $LayoutModel->findSidebarsForLayout();
        $schema = $LayoutModel->findSchemaForLayout();
        
        $sidebars = array_intersect_key($sidebars, $schema['sidebars']);
        
        if (isset($sidebars[$orientation])) {
            $divClass = 'sidebar span3 ' . $orientation . '-sidebar';
            $divClass = apply_filters('gumm_filter_sidebar_class', $divClass, $sidebars[$orientation]['id'], $orientation);
            echo '<div class="' . $divClass . '">';
            if(function_exists('dynamic_sidebar') && dynamic_sidebar($sidebars[$orientation]['id'])) {}
            echo '</div>';
        }
    }
    
    /**
     * @return string
     */
    public function getContentClass() {
        $contentClass = array();
        
        $LayoutModel = GummRegistry::get('Model', 'Layout');

        $layoutPage = $LayoutModel->getCurrentLayoutPage();
        switch ($layoutPage) {
         case 'index':
            break;
         case 'blog':
            $contentClass[] = 'blog-loop';
            $contentClass[] = $LayoutModel->findLayoutType('blog-loop');
            break;
         case 'blog-post':
            $contentClass[] = 'single-post';
            $contentClass[] = $LayoutModel->findLayoutType('blog-post');
            break;
         case 'portfolio':
            $contentClass[] = $LayoutModel->findLayoutType('portfolio-loop');
            $contentClass[] = 'portfolio-loop';
            break;
         case 'portfolio-post':
            $contentClass[] = 'single-portfolio';
            // $contentClass[] = $LayoutModel->findLayoutType('portfolio-post');
            break;
        }

        $contentClass[] = $this->getSidebarsClass();
        
        return implode(' ', Set::filter($contentClass));
    }
    
    public function getPrimeNavClass() {
        $class = array();
        if ($mainNavStyle = $this->getNavMainStyle()) {
            $class[] = $mainNavStyle;
        }
        if ($dropNavStyle = $this->getNavDropStyle()) {
            $class[] = $dropNavStyle;
        }
        
        return $class;
    }
    
    public function getNavMainStyle() {
        return $this->Wp->getOption('nav_main_style');
    }
    
    public function getNavDropStyle() {
        return $this->Wp->getOption('nav_drop_style');
    }
    
    /**
     * @return string
     */
    public function getSidebarsClass() {
        $layoutSchema = GummRegistry::get('Model', 'Layout')->findSchemaStringForLayout();
        switch($layoutSchema) {
         case 'none':
            $class = 'no-sidebars';
            break;
         default:
            $class = $layoutSchema;
            break;
        }
        
        return $class;
    }
    
    /**
     * @return string
     */
    public function adminFields($optionId, $contentTag) {
        $optionSettings = array('div' => false, 'label' => false);
        
        $layoutOptions = $this->Wp->getOption($optionId);
        $outputHtml = '';
        foreach ($this->adminFieldsToEdit as $fieldId => $fieldValue) {
            $id = $optionId . '.' . $contentTag . '.' . $fieldId;
            if (is_array($fieldValue)) $id .= '.{n}';
            
            $htmlAttributes = array('class' => $fieldId);
            if (isset($layoutOptions[$contentTag]) && isset($layoutOptions[$contentTag][$fieldId])) {
                $htmlAttributes['value'] = $layoutOptions[$contentTag][$fieldId];
            }
            
            $outputHtml .= $this->Form->input('Option', array('id' => $id, 'type' => 'hidden'), $htmlAttributes, $optionSettings);
        }
        
        return $outputHtml;
    }
    
    // ======= //
    // WIDGETS //
    // ======= //
    
    /**
     * @param array $options
     * @return string
     */
    public function colorpicker(array $options=array()) {
        $options = array_merge(array(
            'color' => false,
            'input' => false,
            'inputAttributes' => array(),
            'inputSettings' => array('div' => false, 'label' => false),
            'id' => 'colorpicker-' . uniqid(),
            'class' => '',
            'width' => null,
            'height' => null,
            'style' => null,
            'text' => '',
            'label' => null,
            'callbacks' => array(),
            'clearButton' => false,
        ), $options);
        
        extract($options, EXTR_OVERWRITE);
        
        $styleAttributes = array();
        if (strpos($color, '#') === false && $color) {
            $styleAttributes['background-color'] = '#' . $color;
        }
        if ($width) $styleAttributes['width'] = $width . 'px';
        if ($height) $styleAttributes['height'] = $height . 'px';
        
        $styleAttributeString = '';
        foreach ($styleAttributes as $prop => $val) $styleAttributeString .= $prop . ':' . $val . ';';
        if ($style) $styleAttributeString .= $style;
        
        $styleAttribute = '';
        if ($styleAttributeString) $styleAttribute = 'style="' . $styleAttributeString . '"';
        if ($input && is_array($input)) {
            if ($color !== '#' && !isset($inputAttributes['value'])) $inputAttributes['value'] = str_replace('#', '', $color);
            $inputAttributes['class'] = (isset($inputAttributes['class'])) ? $inputAttributes['class'] . ' ' . $id : $id;
            $input = $this->Form->input('', $input, $inputAttributes, $inputSettings);
        } else {
            $input = '';
        }
        
        $this->colorpickersSettings[] = $options;
        
        $outputHtml = '<div class="span2 colorpickerbox">';
        if ($options['clearButton']) $outputHtml .= '<a href="#" class="admin-close-button admin-clear-color">×</a>';
        $outputHtml .= '<div id="' . $id . '" class="color-palette">';
            $outputHtml .= '<div ' . $styleAttribute . '></div>';
            if ($options['label']) $outputHtml .= '<span class="after-detail">' . $options['label'] . '</span>';
        $outputHtml .= '</div>';
        $outputHtml .= $input;
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    public function gaugeChart($params=array()) {
        $params = array_merge(array(
            'size' => null,
            'percent' => 0,
            'color' => '#' . $this->Wp->getOption('styles.color_options.color_option_1'),
            'backgroundColor' => null,
            'strokeWidth' => null,
            'icon' => '',
            'symbol' => '%',
            'iconStyle' => 'plain',
            'fontSize' => 12,
            'animationSpeed' => 300,
        ), $params);

        
        extract($params, EXTR_OVERWRITE);
        
        $canvasAtts = array(
            'class' => 'bb-gauge-chart',
            'width' => $size,
            'height' => $size,
            'data-width' => $size,
            'data-height' => $size,
            'data-percent' => $percent,
            'data-color' => $color,
            'data-background-color' => $backgroundColor,
            'data-line-width' => $strokeWidth,
            'data-text-source' => $iconStyle,
            'data-font-size' => $fontSize,
            'data-animation-speed' => $animationSpeed,
        );
        if ($this->Wp->getOption('enable_effects') === 'true') {
            $canvasAtts['class'] .= ' not-initialized gumm-scrollr-item';
        }
        
        $canvasTextBoxId = null;
        $canvasTextBox = '';
        if ($iconStyle !== 'plain') {
            $canvasTextBoxId = 'canvas-text-box-' . uniqid();
            if ($iconStyle === 'symbol') {
                $canvasTextBox = '<span id="' . $canvasTextBoxId . '" class="canvas-text-box"><span class="number"></span>' . $symbol . '</span>';
            } elseif ($iconStyle === 'icon') {
                $spanAtts = array(
                    'id' => $canvasTextBoxId,
                    'class' => 'canvas-text-box',
                    'style' => 'position: absolute; top: 50%; left: 50%; font-size: ' . $fontSize .'px; line-height: ' . $fontSize . 'px; color: ' . $color . '; margin-left: -' . $fontSize/2 . 'px; margin-top: -' . $fontSize/2 . 'px;'
                );
                $canvasTextBox = '<span' . $this->_constructTagAttributes($spanAtts) . '><i class="' . $icon . '"></i></span>';
            }
        }
        if ($canvasTextBoxId) {
            $canvasAtts['data-text-box'] = '#' . $canvasTextBoxId;
        }

        $outputHtml = '<span class="canvas-gauge-element">';
        $outputHtml .= '<canvas' . GummRegistry::get('Helper', 'Html')->_constructTagAttributes($canvasAtts) .'></canvas>';
        $outputHtml .= $canvasTextBox;
        $outputHtml .= '</span>';
        
        return $outputHtml;
    }
    
    /**
     * @DEPRICATED
     * 
     * @param array $option
     * @return string
     */
    public function getAdminActionsHtmlForOption($option) {
        if (!isset($option['adminActions'])) return '';
        if (!is_array($option['adminActions'])) return '';
        
        $outputHtml = '';
        $outputHtml .= '<div class="row-fluid buttons-container">';
        
        foreach ($option['adminActions'] as $name => $params) {
            $title = $name;
            $url = '';
            switch ($name) {
             case 'save':
                $title = __('save', 'gummfw');
                $url = array('controller' => 'options', 'action' => 'save', 'admin' => true, 'ajax' => true);
                break;
             case 'edit':
                $title = __('edit', 'gummfw');
                $url = array('controller' => 'options', 'action' => 'edit', 'admin' => true, 'ajax' => true);
                break;
            }
            $params = array_merge(array(
                'title' => $title,
                'url' => $url,
                'htmlSettings' => array('class' => 'btn btn-admin-top-right ' . $name),
                'linkSettings' => array(),
            ), (array) $params);
            
            extract($params, EXTR_OVERWRITE);
            
            if (is_array($url) && isset($url['optionId']) && $url['optionId'] === true) $url['optionId'] = $option['id'];
            
            $outputHtml .= $this->Html->link(ucwords($title), $url, $htmlSettings, $linkSettings);
        }
        
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * Returns number of sidebars for current layout
     * 
     * @return int
     */
    public function numSidebars() {
        $schema = GummRegistry::get('Model', 'Layout')->findSchemaForLayout();
        return count($schema['sidebars']);
    }
    
    // =============== //
    // HTML GENERATION //
    // =============== // 
    
    /**
     * @return void
     */
    public function contentTagOpen() {
        $tagClass = 'main-content ' . $this->getSpanContext();
?>
        <div id="main-content" class="<?php echo $tagClass; ?>">
<?php
    }
    
    /**
     * @return void
     */
    public function contentTagClose() {
?>
        </div>
<?php
    }
    
    /* The following methods output html tags for usage in a structured and responsive/fluid layout */
    
    /**
     * Start html row output
     * 
     * @TODO include fluid vs responsive functionality here
     * 
     * @return string
     */
    public function row($additionalClass='') {
?>
        <div class="row-fluid <?php echo $additionalClass; ?>">
<?php
    }
    
    /**
     * End row output
     * 
     * @return string
     */
    public function rowEnd() {
?>
        </div>
<?php
    }
    
    // ================== //
    // LAYOUT VAR GETTERS //
    // ================== //
    
    public function getSpanContext() {
        $schema = GummRegistry::get('Model', 'Layout')->findSchemaForLayout();
        $numSidebars = count($schema['sidebars']);
        
        $context = 'span12';
        switch($numSidebars) {
         case 1:
            $context = 'span8';
            break;
         case 2:
            $context = 'span4';
            break;
        }
        
        return $context;
    }
    
    // ========== //
    // WP FILTERS //
    // ========== //
    
    /**
     * Currently not used as WordPress Filter as these classes are appended to the content div
     * 
     * @return string
     */
    public function _filterBodyClass($class) {
        $class['gummLayoutSchema'] = $layoutSchema = GummRegistry::get('Model', 'Layout')->findSchemaStringForLayout();
        
        if (is_category()) $class[] = 'blog';

        if ($layoutSchema == 'l-c' || $layoutSchema == 'c-r') {
            $class[] = 'one-sidebar';
        } elseif ($layoutSchema == 'l-c-r') {
            $class[] = 'two-sidebars';            
        } elseif ($layoutSchema == 'none') {
            $class[] = 'no-sidebars';
            unset($class['gummLayoutSchema']);
        }

        return array_values($class);
    }
    
    // ==================== //
    // WP ACTIONS & FILTERS //
    // ==================== //
    
    /**
     * @param array $class
     * @return arary
     */
    public function _filterPostClass($class=array()) {
        global $post;
        
        if (!$class && !is_array($class)) $class = array();
        
        if (!isset($post->Media) || !isset($post->Thumbnail)) return $class;
        
        $postMediaTypes = array_unique(Set::classicExtract($post->Media, '{n}.type'));

        if ($post->Thumbnail) $postMediaTypes = array_unique(array_merge(array($post->Thumbnail->type), $postMediaTypes));
        
        if (!$postMediaTypes) {
            $class[] = 'no-media';
        } else {
            $class[] = 'media';
            foreach ($postMediaTypes as $mediaType) {
                 $class[] = 'media-' . $mediaType;
            }
        }

        return $class;
    }
    
    /**
     * @return void
     */
    public function _actionPrintColorPickerJs($ajax=false) {
        $groups = Set::groupMulti($this->colorpickersSettings, 'id', 'callbacks');
        
        if (!$groups) return;
        
        foreach ($groups as $groupSettings) {
            $groupSettings['id'] = '#' . implode(', #', Set::filter($groupSettings['id']));
            $this->colorPickerJs($groupSettings, $ajax);
        }
        
    }
    
    /**
     * @param array $settings
     * @return void
     */
    public function colorPickerJs($settings, $ajax) {
        
        extract($settings, EXTR_OVERWRITE);
?>

<script type="text/javascript">
//<![CDATA[
    try {
        jQuery('<?php echo $id; ?>').ColorPicker({
            onBeforeShow: function () {
                var theEle = jQuery(this);

                var theInput = jQuery('.' + theEle.attr('id'));
                // If input enabled and no color - change colorpicker value
                theEle.ColorPickerSetColor(theInput.val());
                
                jQuery(this).data('gummColorPickerInput', theInput);
            },
            onShow: function (colpkr) {
                if (jQuery(colpkr).is(':visible')) {
                    jQuery(colpkr).stop(true, true).fadeOut(100);
                } else {
                    jQuery(colpkr).stop(true, true).fadeIn(100);
                }
                return false;
            },
            onHide: function (colpkr) {
                jQuery(colpkr).stop(true, true).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                var theEle = jQuery(jQuery(this).data('colorpicker').el);

                theEle.children('div').css({
                    backgroundColor: '#' + hex
                });
                
                // If input enabled - change its value
                theEle.data('gummColorPickerInput').val(hex);

                <?php if (isset($callbacks['onChange'])): ?>
                <?php echo $callbacks['onChange'];?>.call(this, {hsb: hsb, hex: hex, rgb: rgb});
                <?php endif; ?>
            },
            onSubmit: function(hsb, hex, rgb, el) {
                var theEle = jQuery(this.el);

                theEle.children('div').css({
                    backgroundColor: '#' + hex
                });
                
                // If input enabled - change its value
                theEle.data('gummColorPickerInput').val(hex);

                <?php if (isset($callbacks['onChange'])): ?>
                <?php echo $callbacks['onChange'];?>.call(this, {hsb: hsb, hex: hex, rgb: rgb});
                <?php endif; ?>
                
                theEle.ColorPickerHide();
            }
        });
    }catch(err){};

//]]>
</script>

<?php
    }
    
    public function _actionPrintGummSwitchJs() {
        if (!$this->gummSwitchJsSettings) return;
?>

<script type="text/javascript">
//<![CDATA[
    try {
        <?php foreach ($this->gummSwitchJsSettings as $containerClass => $settings): ?>
        jQuery('.<?php echo $containerClass;?>').gummSwitch({
            change: function(stateOpen) {
                if (!stateOpen) {
                    var panelToOpen = jQuery(this).next('.background-pattern-schemes').children().eq(0);
                    var panelToHide = jQuery(this).next('.background-pattern-schemes').children().eq(1);
                } else {
                    var panelToOpen = jQuery(this).next('.background-pattern-schemes').children().eq(1);
                    var panelToHide = jQuery(this).next('.background-pattern-schemes').children().eq(0);
                }
                panelToOpen.show();
                panelToHide.hide();
                var currentSelected = panelToHide.find('input.gumm-slider-input').filter(':checked').val();
                panelToOpen.find('input.gumm-slider-input[value=' + currentSelected + ']').attr('checked', 'checked').trigger('change');
            }
        });
        <?php endforeach; ?>
        
        jQuery('.background-pattern-schemes input.gumm-slider-input').bind('change', function(e){
            var theUrl = jQuery(this).attr('title');
            var theEditor = jQuery(this).parents('.block-background-patterns-editor:first');
            theEditor.children('input.block-background-pattern-url').val(theUrl);
            theEditor.children('div.block-background-pattern-preview').children('div').css({
                backgroundImage: "url('" + theUrl + "')"
            });
        });
        
    }catch(err){};

//]]>
</script>
<?php
    }

}
?>