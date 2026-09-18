<?php
class PageBuilderHelper extends GummHelper {
    /**
     * @var array
     */
    public $helpers = array('Wp', 'Html', 'Media', 'Form', 'Cookie');
    
    protected $SidebarModel;
    
    protected $leftSidebar;
    protected $rightSidebar;
    protected $elementsAvailable = array();
    protected $elementsEnabled = array();
    private $_editorMode;
    private $_activeTabIndex;
    private $_includeSingleElements = false;
    
    
    public function __construct() {
        parent::__construct();
        
        $this->SidebarModel = GummRegistry::get('Model', 'Sidebar');
    }
    
    public function editor($options=array()) {
        $options = array_merge(array(
            'leftSidebar' => $this->SidebarModel->getDefaultForOrientation('left'),
            'rightSidebar' => $this->SidebarModel->getDefaultForOrientation('right'),
            'layoutSchema' => 'none',
            'elementsEnabled' => array(),
            'elementsAvailable' => array(),
            'schemaInputsContainerSelector' => null,
            'builderAttributes' => array(),
            'editorAttributes' => array(
                'class' => 'gumm-page-builder-editor',
            ),
            'editorMode' => 'full',
            'model' => 'Option',
            'optionId' => null,
            'metaKey' => null,
            'postId' => null,
        ), $options);
        
        extract($options, EXTR_OVERWRITE);
        if ($editorMode === 'fullsingle') {
            $this->_includeSingleElements = true;
            $editorMode = 'full';
        }
        
        $this->_editorMode       = $editorMode;
        $this->elementsAvailable = $elementsAvailable;
        $this->elementsEnabled   = $elementsEnabled;

        $builderAttributes = array_merge(array(
            'class' => array(
                'admin-page-builder',
                'layout-schema-' . $layoutSchema,
            ),
        ), $builderAttributes);
        
        $editorAttributes = array_merge(array(
            'data-schema-inputs-container' => $schemaInputsContainerSelector,
            'data-sidebar-schema' => $layoutSchema,
            'data-editor-mode' => $this->_editorMode,
            'data-active-sidebar-left' => $leftSidebar['id'],
            'data-active-sidebar-right' => $rightSidebar['id'],
            'data-post-id' => $postId,
            'data-model' => $model,
            'data-meta-key' => $metaKey,
        ), $editorAttributes);
        
        $editorAttributes['class'] = (array) $editorAttributes['class'];
        $editorAttributes['class'][] = 'mode-' . $editorMode;
        
        ob_start();
?>
        <div<?php echo $this->Html->_constructTagAttributes($editorAttributes); ?>>
            
            <?php echo $this->editorElementsTabs($editorMode); ?>
            
            <div<?php echo $this->Html->_constructTagAttributes($builderAttributes); ?>>
                
                <!-- The Header Block -->
                <div class="row-fluid">
                    <div class="span12 header-content-area">
                        <div class="admin-content-builder">
                            <div class="sortable-elements-wrapper sortable-elements-header" data-model-position="header">
                            <?php
                            if ($editorMode === 'full') {
                                foreach ($this->elementsEnabled['header'] as $element) {
                                    // echo '<div class="row-fluid">';
                                    $element->setLayoutPosition('header');
                                    $element->editor();
                                    // echo '</div>';
                                }
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- The Content & Sidebars Block -->
                <div class="row-fluid">
                    <div class="span4 left-sidebar sidebar">
                        <div class="admin-content-builder">
                            <div class="admin-builder-element" data-element-id="<?php echo $leftSidebar['id']; ?>">
                                <div class="element-content"><?php echo $leftSidebar['name']; ?></div>
                            </div>
                            <?php
                            if ($optionId) {
                                echo $this->Form->input($model, array(
                                    'id' => $this->constructOptionId('sidebars', $optionId) . '.left',
                                    'type' => 'hidden',
                                ), array(
                                    'value' => $leftSidebar['id'],
                                    'class' => 'sidebar-value',
                                    // 'disabled' => 'disabled',
                                ));
                            }
                            ?>
                        </div>
                    </div>

                    <div class="span8 content-area">
                        <div class="admin-content-builder">
                            <div class="sortable-elements-wrapper sortable-elements-content" data-model-position="content">
                            <?php
                            if ($editorMode === 'full') {
                                $counterWidthRatio = 0;
                                foreach ($this->elementsEnabled['content'] as $element) {
                                    $element->setLayoutPosition('content');
                                    $element->editor();
                                }
                            }
                            ?>
                            </div>
                        </div>
                    </div>

                    <div class="span4 right-sidebar sidebar">
                        <div class="admin-content-builder">
                            <div class="admin-builder-element" data-element-id="<?php echo $rightSidebar['id']; ?>">
                                <div class="element-content"><?php echo $rightSidebar['name']; ?></div>
                            </div>
                            <?php
                            if ($optionId) {
                                echo $this->Form->input($model, array(
                                    'id' => $this->constructOptionId('sidebars', $optionId) . '.right',
                                    'type' => 'hidden',
                                ), array(
                                    'value' => $rightSidebar['id'],
                                    'class' => 'sidebar-value',
                                    // 'disabled' => 'disabled',
                                ));
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
<?php
        return ob_get_clean();
    }
    
    private function editorElementsTabs($mode='full') {
        if ($mode === 'none') return '';
        
        $tabs           = array();
        $layoutElements = array();
        if ($mode === 'full') {
            $layoutElements = $this->getLayoutElements();
        }
        
        $configElements = Configure::read('Data.BuilderElements');
        foreach ($configElements as $groupId => $params) {
            if ($mode !== 'full' && $groupId !== 'sidebars') {
                continue;
            } elseif ($this->_includeSingleElements === false && $groupId === 'single') {
                continue;
            } elseif (!$params['elements'] && $groupId !== 'sidebars') {
                continue;
            }
            
            $tabs[$groupId] = array(
                'title' => $params['title'],
            );
            if ($groupId === 'sidebars') {
                $tabs[$groupId]['items'] = $this->getSidebarElements();
            } elseif (isset($layoutElements[$groupId])) {
                $tabs[$groupId]['items'] = $layoutElements[$groupId];
            }
            
        }
        
        $outputHtml = '';
        $outputHtml .= '<div class="builder-elements-tabs">';
            $outputHtml .= '<ul class="nav nav-tabs">';
            
            $activeTab = $this->activeTabIndex();
            $counter = 0;
            foreach ($tabs as $tab) {
                $liAtts = array(
                    'class' => array('builder-element'),
                );
                if ($counter === $activeTab) {
                    $liAtts['class'][] = 'active';
                }
                
                $outputHtml .= '<li' . $this->Html->_constructTagAttributes($liAtts) . '>';
                $outputHtml .= '<a href="#">' . $tab['title'] . '</a>';
                $outputHtml .= '</li>';
                
                $counter++;
            }
            
            $outputHtml .= '</ul>';
            
            $outputHtml .= '<div class="row-fluid">';
                $outputHtml .= '<div class="span12">';
                    $outputHtml .= '<div class="bluebox-bulder-toolbar">';
                        $outputHtml .= '<div class="content-wrap">';
                            $outputHtml .= '<div class="scroll-carousel">';
                        
                            $counter = 0;
                            foreach ($tabs as $tab) {
                                $ulAtts = array(
                                    'class' => 'bulder-toolbar-elements-list'
                                );
                                if ($counter !== $activeTab) {
                                    $ulAtts['style'] = 'display:none;';
                                }
                                $outputHtml .= '<ul' . $this->Html->_constructTagAttributes($ulAtts) . '>';
                            
                                foreach ($tab['items'] as $tabItem) {
                                    $outputHtml .= $tabItem;
                                }
                            
                                $outputHtml .= '</ul>';
                                $counter++;
                            }
                            $outputHtml .= '</div>';
                        
                        $outputHtml .= '</div>';
                        $outputHtml .= '<a class="bb-toolbar-arrow toolbar-prev icon-chevron-left" href="#"></a>';
                        $outputHtml .= '<a class="bb-toolbar-arrow toolbar-next icon-chevron-right" href="#"></a>';
                        $outputHtml .= '<div class="scroll-toolbar-mask"></div>';
                    $outputHtml .= '</div>';
                $outputHtml .= '</div>';
            $outputHtml .= '</div>';
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    private function getSidebarElements() {
        $sidebars = GummRegistry::get('Model', 'Sidebar')->find('all', array(
            'conditions' => array(
                'id !=' => array(
                    'gumm-footer-sidebar-1',
                    'gumm-footer-sidebar-2',
                    'gumm-footer-sidebar-3',
                    'gumm-footer-sidebar-4'
                ),
            ),
        ));
        
        $elements = array();

        foreach ($sidebars as $sidebar) {
            $elements[] = $this->requestAction(array('admin' => true, 'controller' => 'sidebars', 'action' => 'view', $sidebar), false);
        }
        
        $elements[] = '<li class="new-sidebar icon-plus"></li>';

        return $elements;
    }
    
    private function getLayoutElements() {
        $elementsList = array();
        foreach ($this->elementsAvailable as $group => $elements) {
            $elementsList[$group] = array();
            foreach ($elements as $element) {
                if ($this->_includeSingleElements === false && $element->group() === 'single') continue;
                 
                $liAtts = array(
                    'class' => 'builder-toolbar-element element-layout-element',
                    'data-layout-position' => $element->getLayoutPosition(),
                    'data-element-group' => $element->group(),
                    'data-element-id' => $element->id(),
                    'data-element-title' => $element->title(),
                );
                $elementsList[$group][] = '<li' . $this->Html->_constructTagAttributes($liAtts) . '>' . $element->title() . '</li>';
            }
        }
        
        return $elementsList;
    }
    
    public function activeTabIndex() {
        if (!$this->_activeTabIndex) {
            $this->_activeTabIndex = 0;
            if ($this->_editorMode !== 'sidebar') {
                $this->_activeTabIndex = (int) $this->Cookie->read('admin.pageBuilderActiveTab');
            }
        }
        
        return $this->_activeTabIndex;
    }
}
?>