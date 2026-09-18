<?php
abstract class GummLayoutElement extends GummObject {
    abstract public function title();
    // abstract public function group();
    abstract protected function _fields();
    abstract protected function _render($options);
    
    const GENERIC_ID = 'generic-id';
    
    /**
     * @var array
     */
    public $helpers = array(
        'Text',
        'Media',
        'Form',
        'Wp',
        'Html',
        'Layout',
        'Time',
    );
    
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    public $group;
    
    /**
     * @var string
     */
    protected $model;
    
    /**
     * @var int
     */
    protected $gridColumns = 4;
    
    /**
     * @var mixed bool/string
     */
    protected $editable = true;
    
    /**
     * @var bool
     */
    protected $fullWidthEditor = false;
    
    /**
     * @var string
     */
    protected $htmlClass = '';
    
    /**
     * @var array
     */
    protected $params = array(
        'widthRatio' => 1,
    );
    
    /**
     * @var array
     */
    protected $renderSettings = array();
    
    /**
     * Predefined functionality for an element
     * Valid values are 'title', 'excerpt', 'description', 'endToEnd', 'postsNumber', 'postType'
     * 
     * @var array
     */
    protected $supports = array(
        'title'
    );
    
    /**
     * If element supports layout, include custom layout options here
     * 
     * @var array
     */
    protected $layoutsAvailable = array();
    
    /**
     * @var int
     */
    protected $postId = null;
    
    /**
     * @var int
     */
    protected $posts = null;    
    
    /**
     * Defines where the element can be situated in the layout.
     * Possible values are: 'content', 'all'
     * Defaults to 'content'
     * 
     * @var string
     */
    protected $layoutPosition = 'content';
    
    /**
     * Holds the original position, kept when changed by the instance
     * 
     * @var string
     */
    protected $originalLayoutPosition;
    
    /**
     * @var string
     */
    public $metaKey = 'layout_components';
    
    /**
     * @var bool
     */
    protected $endToEnd = false;
    
    /**
     * @var string
     */
    protected $htmlElementId;
    
    /**
     * @var array
     */
    protected $htmlElementData = array();
    
    /**
     * @var bool
     */
    protected $shouldPaginate = false;
    
    /**
     * @var string
     */
    protected $queryPostType = 'post';
    
    /**
     * @var array
     */
    protected $queryArgs;
    
    /**
     * @var bool
     */
    protected $resetQueryAfterRender = false;
    
    /**
     * @var array
     */
    private $errors = array();
    
    public $noMargin = false;
    
    /**
     * @var array
     */
    private $mediaItems = array();
    
    /**
     * @var WP_Query
     */
    private $_originalQuery;
    
    /**
     * @param array $data
     * @return void
     */
    public function __construct($data=array()) {
        parent::__construct();
        
        foreach ($this->helpers as $helper) {
            $this->$helper = GummRegistry::get('Helper', $helper);
        }
        
        // foreach ($this->helpers as $helper)

        if (isset($data['settings'])) {
            $this->setParams($data['settings']);
        }
        if (isset($data['postId'])) {
            $this->postId = $data['postId'];
        }
        
        if(!$this->name) {
            $this->name = str_replace('_layout_element', '', Inflector::underscore(get_class($this)));
        }
        $this->htmlElementId = Inflector::slug($this->name, '-') . '-element-' . uniqid();
        
        if ($this->supports('plugin')) {
            $this->isPluginInstalled(true);
        }
        
        $this->originalLayoutPosition = $this->layoutPosition;
        
        $this->initialize();
    }
    
    /**
     * @param array $paams
     * @return void
     */
    public function setParams($params) {
        if (isset($params['posts'])) {
            $this->posts = $params['posts'];
            unset($params['posts']);
        }
        if (isset($params['mediaItems'])) {
            $this->mediaItems = $params['mediaItems'];
            unset($params['mediaItems']);
        }
        $this->params = array_merge($this->params, $params);
    }
    
    /**
     * @param string $param
     * @param mixed $value
     * @return void
     */
    public function setParam($param, $value) {
        $this->params[$param] = $value;
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function id($id=null) {
        if ($id) $this->id = $id;
        
        return $this->id;
    }
    
    public function group($group=null) {
        if ($group) $this->group = $group;
        
        return $this->group;
    }
    
    /**
     * @param string $model
     * @return string
     */
    public function model($model=null) {
        if ($model) $this->model = $model;
        
        return $this->model;
    }
    
    /**
     * @param bool $private 
     * @return array
     */
    public function fields($private=false) {
        $fields = array();
        if (!$this->getErrors() && $private === false) {
            if ($this->supports('title')) $fields = array_merge($fields, $this->_titleFields());
            if ($this->supports('media')) $fields = array_merge($fields, $this->_mediaFields());
            if ($this->supports('description')) $fields = array_merge($fields, $this->_descriptionFields());
            if ($this->supports('layout')) $fields = array_merge($fields, $this->_layoutFields());
            if ($this->supports('postType')) $fields = array_merge($fields, $this->_postTypeFields());
            if ($this->supports('postColumns')) $fields = array_merge($fields, $this->_postColumnsFields());
            if ($this->supports('postsNumber')) $fields = array_merge($fields, $this->_postsNumberFields());
            if ($this->supports('categoriesFilter')) $fields = array_merge($fields, $this->_categoriesFilterFields());
            if ($this->supports('excerpt')) $fields = array_merge($fields, $this->_excerptFields());
            if ($this->supports('endToEnd')) $fields = array_merge($fields, $this->_endToEndFields());
            if ($this->supports('categories')) $fields = array_merge($fields, $this->_categoriesFields());
            if ($this->supports('aspectRatio')) $fields = array_merge($fields, $this->_getAspectRatioFields());
            if ($this->supports('sliderFields')) $fields = array_merge($fields, $this->_getSliderFields());
            if ($this->supports('paginationLinks')) $fields = array_merge($fields, $this->_getPaginationFields());
            if ($this->supports('metaFields')) $fields = array_merge($fields, $this->_getMetaFields());
            
            $fields = array_merge($fields, $this->_fields());
        }
        
        return $fields;
    }
    
    /**
     * @return array
     */
    protected function _titleFields() {
        $val = (isset($this->supports['title'])) ? $this->supports['title'] : '';
        if ($this->layoutPosition == 'header') return array();
        
        return array(
            // 'enableHeading' => array(
            //     'name' => __('Use heading line for this element', 'gummfw'),
            //     'type' => 'checkbox',
            //     'value' => 'true',
            //     'inputAttributes' => array(
            //         'class' => 'gumm-input enable-heading-switch'
            //     ),
            // ),
            'headingText' => array(
                'name' => __('Heading Text', 'gummfw'),
                'type' => 'text',
                'value' => $val,
                'before' => '<div class="heading-line-inputs">',
                'after' => '</div>',
            ),
            // 'headingColor' => array(
            //     'name' => __('Heading Text Color (if left blank, theme default color will be used)', 'gummfw'),
            //     'type' => 'colorpicker',
            //     'value' => '',
            //     'after' => '</div>',
            // ),
        );
    }
    /**
     * @return array
     */
    protected function _mediaFields() {
        $contentFields = array_merge(
            $this->_postTypeFields(),
            $this->_postsNumberFields()
        );
        $mediaUploadFields = array(
            'media' => array(
                'name' => '',
                'type' => 'media',
            ),
        );
        $fields = array(
            'mediaSource' => array(
                'name' => __('Media source', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'post' => __('Post', 'gummfw'),
                    'custom' => __('Custom', 'gummfw'),
                ),
                'value' => 'post',
                'tabs' => array(
                    $contentFields,
                    $mediaUploadFields,
                ),
            ),
        );
        
        return $fields;
    }
    
    /**
     * @return array
     */
    protected function _descriptionFields() {
        return array(
            'descriptionText' => array(
                'name' => __('Block description text', 'gummfw'),
                'type' => 'text-editor',
                'inputSettings' => array(
                    'div' => 'input-wrap wrap-text-editor',
                )
            ),
        );
    }
    
    /**
     * @return array
     */
    protected function _layoutFields() {
        $inputOptions = array_merge(array(
            'grid' => __('Grid Layout', 'gummfw'),
            'slider' => __('Row Slider Layout', 'gummfw'),
        ), $this->layoutsAvailable);
        $val = (isset($this->supports['layout'])) ? $this->supports['layout'] : 'grid';
        
        return array(
            'layout' => array(
                'name' => __('Element layout', 'gummfw'),
                'type' => 'radio',
                'value' => $val,
                'inputOptions' => $inputOptions,
            ),
        );
    }
    
    /**
     * @return array
     */
    protected function _endToEndFields() {
        return array(
            'endToEnd' => array(
                'name' => __('Display element as end-to-end <em>(4 column element only)</em>', 'gummfw'),
                'type' => 'checkbox',
            ),
        );
    }
    
    /**
     * @return array
     */
    protected function _postsNumberFields() {
        $val = (isset($this->supports['postsNumber'])) ? $this->supports['postsNumber'] : 4;
        
        $fields = array(
            'postsNumber' => array(
                'name' => __('Number of posts to display', 'gummfw'),
                'type' => 'number',
                'value' => $val,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 1,
                        'max' => 100,
                        'maxTitle' => __('All', 'gummfw'),
                        'maxValue' => '-1',
                        'numberType' => ''
                    ),
                ),
            ),
        );
        
        // if ($this->supports('postsPagination')) {
        //     $val = (isset($this->supports['postsPagination'])) ? $this->supports['postsPagination'] : 3;
        //     
        //     $fields['postsPagination'] = array(
        //         'name' => __('Maximum number of pages to slide to', 'gummfw'),
        //         'type' => 'number',
        //         'value' => $val,
        //         'inputSettings' => array(
        //             'slider' => array(
        //                 'min' => 1,
        //                 'max' => 10,
        //                 'numberType' => ''
        //             ),
        //         ),
        //     );
        // }
        
        return $fields;
    }
    
    protected function _postColumnsFields($settings=array()) {
        $val = 4; $min = 3; $max = 4; $skip = array();
        if (isset($this->supports['postColumns'])) {
            $settings = (array) $this->supports['postColumns'];
        }
        
        $val  = isset($settings['value']) ? $settings['value'] : $val;
        $min  = isset($settings['min']) ? $settings['min'] : $min;
        $max  = isset($settings['max']) ? $settings['max'] : $max;
        $skip = isset($settings['skip']) ? (array) $settings['skip'] : $skip;

        $range = range($min, $max);
        $inputOptions = array();
        foreach ($range as $num) {
            if (in_array($num, $skip)) continue;
            $inputOptions[$num] = $num;
            if ($num === 1) {
                $inputOptions[$num] .= ' ' . __('column', 'gummfw');
            } else {
                $inputOptions[$num] .= ' ' . __('columns', 'gummfw');
            }
        }
        
        return array(
            'postColumns' => array(
                'name' => __('Number of columns', 'gummfw'),
                'type' => 'select',
                'value' => $val,
                'inputOptions' => $inputOptions,
            ),
        );
    }
    
    protected function _postTypeFields() {
        if (isset($this->params['postType'])) $val = $this->params['postType'];
        elseif (isset($this->supports['postType']) && isset($this->supports['postType']['value'])) $val = $this->supports['postType']['value'];
        elseif (isset($this->supports['postType'])) $val = $this->supports['postType'];
        else $val = 'post';
        
        $postTypes = array_merge(array('post' => 'post'), get_post_types(array('capability_type' => 'post', '_builtin' => false, 'public' => true)));
        $postTypes = Set::applyNative($postTypes, 'ucwords');

        $fields = array(
            'postType' => array(
                'name' => __('Data Source', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => $postTypes,
                'value' => $val,
                'inputAttributes' => array(
                    'class' => 'layout-element-post-type-select-input gumm-input',
                ),
                'tabs' => array(
                    
                ),
            ),
        );
        
        foreach ($postTypes as $postType => $postTypeName) {
            $categoryField = $this->_categoriesFields($postType);
            
            $tabContentInput = $categoryField['category'];
            if (count($categoryField['category']['inputOptions']) === 0) {
                $tabContentInput = array('tabText' => __('There are no available categories for this post type.', 'gummfw'));
            }
            $fields['postType']['tabs'][][$postType . '-category'] = $tabContentInput;
        }
        
        if (isset($this->supports['postType']) && isset($this->supports['postType']['flickr']) && $this->supports['postType']['flickr']) {
            $fields['postType']['inputOptions']['flickr'] = __('Flickr', 'gummfw');
            $fields['postType']['tabs'][] = array(
                'flickrUser' => array(
                    'name' => __('Flickr username', 'gummfw'),
                    'type' => 'text'
                ),
            );
        }
        $fields['postType']['inputOptions']['default'] = __('Default', 'gummfw');
        $fields['postType']['tabs'][] = array(
            'tabText' => __('Use default WordPress query.', 'gummfw', 'gummfw'),
        );
        
        
        return $fields;
    }
    
    protected function _categoriesFilterFields() {
        $val = (isset($this->supports['categoriesFilter'])) ? $this->supports['categoriesFilter'] : 'true';
        return array(
            'categoriesFilter' => array(
                'name' => __('Display filterable categories', 'gummfw'),
                'type' => 'radio',
                'value' => $val,
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
            ),
        );
    }
    
    protected function _categoriesFields($postType=null) {
        $val = (isset($this->supports['categories'])) ? $this->supports['categories'] : '';
        $inputOptions = array();
        
        if ($postType === null) $postType = $this->postType;
        $termName = $postType == 'post' ? 'category' : $postType . '_category';
        
        if ($terms = get_terms($termName)) {
            if (!isset($terms->errors)) {
                foreach ($terms as $term) {
                    $inputOptions[$term->term_id] = $term->name;
                }
            }
        }
        
        $fields = array(
            'category' => array(
                'name' => ucwords($postType) . ' ' . __('Category', 'gummfw'),
                'type' => 'checkboxes',
                'inputOptions' => $inputOptions,
                // 'inputSettings' => array(
                //     'div' => 'input-wrap wrap-select post-type-select post-type-select-' . $postType
                // ),
            ),
        );
        
        return $fields;
    }
    
    /**
     * @return array
     */
    protected function _excerptFields() {
        $val = (isset($this->supports['excerptLength'])) ? $this->supports['excerptLength'] : 100;
        
        return array(
            'excerptLength' => array(
                'name' => __('Excerpt length', 'gummfw'),
                'type' => 'number',
                'value' => $val,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 0,
                        'max' => 1000,
                        'step' => 25,
                        'numberType' => ''
                    ),
                ),
            ),
        );
    }
    
    protected function _getAspectRatioFields() {
        $val = (isset($this->supports['aspectRatio'])) ? $this->supports['aspectRatio'] : 1.77;
        
        return array(
            'aspectRatio' => array(
                'name' => __('Media Aspect Ratio', 'gummfw'),
                'type' => 'number',
                'value' => $val,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 0.01,
                        'max' => 5,
                        'step' => 0.01,
                        'numberType' => ''
                    ),
                ),
            ),
        );
    }
    
    protected function _getSliderFields() {
        return array(
            'sliderAutoPlay' => array(
                'name' => __('Slider auto play', 'gummfw'),
                'type' => 'checkbox',
                'value' => 'false'
            ),
            'sliderEffect' => array(
                'name' => __('Slide effect', 'gummfw'),
                'type' => 'select',
                'inputOptions' => array(
                    'slide' => __('Slide', 'gummfw'),
                    'fade' => __('Fade', 'gummfw'),
                ),
                'value' => 'slide',
            ),
            'sliderEffectSpeed' => array(
                'type' => 'number',
                'name' => __('Animation speed (in ms)', 'gummfw'),
                'inputSettings' => array(
                    'slider' => array(
                        'max' => 5000,
                        'step' => 250,
                        'numberType' => ''
                    ),
                ),
                'value' => 500,
            ),
        );
    }
    
    protected function _getPaginationFields() {
        return array(
            'enablePaginate' => array(
                'name' => __('Enable Pagination Links', 'gummfw'),
                'type' => 'radio',
                'value' => 'false',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
            ),
        );
    }
    
    protected function _getMetaFields() {
        $val = (isset($this->supports['metaFields'])) ? $this->supports['metaFields'] : array('author' => 'true', 'date' => 'true', 'categories' => 'true', 'comments' => 'true');
        
        return array(
            'metaFields' => array(
                'type' => 'checkboxes',
                'name' => __('Meta fields', 'gummfw'),
                'inputOptions' => array(
                    'author' => __('Author', 'gummfw'),
                    'date' => __('Date', 'gummfw'),
                    'categories' => __('Categories', 'gummfw'),
                    'comments' => __('Comments', 'gummfw'),
                ),
                'value' => $val,
            ),
        );
    }
    
    /**
     * @param bool $safe
     * @return string
     */
    public function widthRatio($safe=false) {
        $ratio = $this->params['widthRatio'];
        if (GummRegistry::get('Model', 'Layout')->findSchemaStringForLayout() == 'l-c-r') {
            if ($ratio > 0.5) {
                $ratio = 1;
            } else {
                $ratio = 0.5;
            }
        }
        if ($safe) {
            $ratio = Inflector::slug($ratio);
        }
        
        return $ratio;
    }
    
    /**
     * @return int
     */
    public function getColumns() {
        $wr = $this->widthRatio();
        
        $cols = $wr * 4;
        // if (GummRegistry::get('Model', 'Layout')->findSchemaStringForLayout() == 'l-c-r') {
            // $cols = $wr * 2;
        // }
        
        return $cols;
    }
    
    /**
     * @return bool
     */
    public function isEndToEndLayout() {
        $endToEnd = false;
        if ( ($this->getParam('endToEnd') == 'true' || $this->endToEnd) && ($this->widthRatio() == 1) ) {
            $endToEnd = true;
        }
        
        return $endToEnd;
    }
    
    protected function getRowSpan() {
        return (int) ($this->widthRatio() * 12);
    }
    
    /**
     * @return string
     */
    protected function getElementClass() {
        $classes = array(
            'gumm-layout-element',
            Inflector::slug($this->name, '-'). '-layout-element',
            'span' . (string) $this->getRowSpan(),
            $this->htmlClass,
        );
        
        if ($this->getParam('headingText') || $this->shouldPaginate) {
            $classes[] = 'has-title';
        }
        if ($this->getParam('categoriesFilter') === 'true') {
            $classes[] = 'has-filterable-categories';
        }
        if ($this->getParam(''))
        if ($this->layoutPosition === 'header') {
            $classes[] = 'header-element';
        }
        if ($this->supports('layout')) {
            $classes[] = $this->getParam('layout');
        }
        
        $classes = $this->beforeRenderElementClass($classes);
        $classes = array_unique($classes);
        
        return implode(' ', Set::filter($classes));
    }
    
    protected function beforeRenderElementClass($classes) {
        return $classes;
    }
    
    /**
     * @return string
     */
    protected function getElementStyle() {
        return '';
    }
    
    /**
     * @return bool
     */
    public function renderBehindContent() {
        return $this->getParam('renderBehindContent') == 'true';
    }
    
    /**
     * @return bool
     */
    public function isEditable() {
        return (bool) $this->editable;
    }
    
    /**
     * @param mixed $type
     * @return bool
     */
    protected function supports($type) {
        $supports = true;
        if ($type == 'pagination') {
            $supports = $this instanceof GummLayoutElementPaginationInterface;
        } else {
            foreach ((array) $type as $typeToCheck) {
                if (!in_array($typeToCheck, (array) $this->supports) && !array_key_exists($typeToCheck, (array) $this->supports)) {
                    $supports = false;
                    break;
                }
            }
        }
        
        return $supports;
    }
    
    /**
     * @param array $options
     * @return void
     */
    public function render($options=array()) {
        if ($this->beforeRender($options) === false) return '';
        
        $attributes = array_merge(array(
            'id' => $this->htmlElementId,
            'class' => $this->getElementClass(),
            'style' => $this->getElementStyle(),
        ), $this->htmlElementData);
        
        echo '<div' . $this->Html->_constructTagAttributes($attributes) . '>';

        if ($this->supports('title') && $this->layoutPosition == 'content') $this->renderTitle();
        
        if ($this->widthRatio() == 1) $this->Layout->row();
        
        $filterableItems = false;
        
        if ($this->getParam('layout') !== 'slider' && $this->getParam('categoriesFilter') === 'true') {
            View::renderElement('layout-components-parts/categories-list', array('postType' => $this->getParam('postType')));
            $filterableItems = true;
        }
        
        if ($filterableItems) {
            echo '<div class="gumm-filterable-items" data-columns="' . $this->getParam('postColumns') . '">';
        }

        if ($errors = $this->getErrors()) {
            // echo '<p class="not-found">' . implode('</p><p class="not-found">', $errors) . '</p>';
        } else {
            $this->_render($options);
            if ($this->getParam('enablePaginate') === 'true') {
                global $wp_query;
            	if ($wp_query->max_num_pages > 1) {
            	    GummRegistry::get('Helper', 'Pagination')->paginate();
            	}
            }
        }
        
        if ($filterableItems) {
            echo '</div>';
        }
        
        if ($this->widthRatio() == 1) $this->Layout->rowEnd();

        echo '</div>';
        
        if ($this->resetQueryAfterRender) {
            $GLOBALS['wp_query'] = $GLOBALS['wp_the_query'] = $this->_originalQuery;
            wp_reset_query();
        }
    }

    /**
     * @return void
     */
    protected function renderTitle() {
        if (!$this->supports('title')) return;
        // if ($this->getParam('enableHeading') !== 'true') return;
        
        $elementParams = array(
            'elementId' => $this->id(),
            'title' => $this->getParam('headingText'),
            'paginate' => $this->shouldPaginate,
        );
        
        View::renderElement('layout-components-parts/heading', $elementParams);
    }
    
    /**
     * @return string
     */
    protected function getEditorClass() {
        $classes =  array(
            'template-builder-element',
            // 'sidebar-block-demo',
            // 'width-ratio-' . $this->widthRatio(true),
            // 'grid-cols-' . $this->gridColumns,
            // 'element-' . strtolower(Inflector::slug($this->title(), '-')),
            // 'position-' . $this->layoutPosition,
            'position-' . $this->originalLayoutPosition,
            'span' . (string) $this->getRowSpan(),
        );
        
        return implode(' ', $classes);
    }
    
    /**
     * @return void
     */
    public function admin_view() {
?>
        <div class="template-element position-<?php echo $this->layoutPosition; ?> sidebar-block-demo width-ratio-<?php echo $this->widthRatio(true); ?> grid-cols-<?php echo $this->gridColumns; ?>" title="<?php echo $this->title(); ?>">
            <span class="title"><?php echo $this->title(); ?></span>
            <?php echo $this->Html->link('', array('ajax' => true, 'admin' => true, 'controller' => 'layouts', 'action' => 'add_layout_element', 'elementId' => $this->id(), 'postId' => $this->postId), array('style' => 'display: none;', 'class' => 'add_layout_element')); ?>
        </div>
        
<?php
    }
    
    /**
     * @return void
     */
    public function editor() {
        $isAjax = GummRegistry::get('Component', 'RequestHandler')->isAjax();
?>
        <?php
        $divEditorAtts = array(
            'class' => $this->getEditorClass(),
            'data-element-position' => $this->layoutPosition,
            'data-available-position' => $this->originalLayoutPosition,
            'data-span-num' => $this->getRowSpan(),
            'data-grid-columns' => $this->gridColumns,
        );
        ?>
        <div<?php echo $this->Html->_constructTagAttributes($divEditorAtts); ?>>
            <div class="admin-builder-element">
                <a href="#" class="admin-close-button">×</a>
                <div class="element-content">
                    <h6><?php echo $this->title(); ?></h6>
                </div>

                <?php if ($this->isEditable()): ?>
                <?php
                $linkClass = array(
                    'edit-button'
                );
                if ($this->fullWidthEditor) {
                    $linkClass[] = 'full-width-editor';
                }
                ?>
                <a href="#" class="icon-pencil admin-element-edit"></a>
                <?php endif;?>

                <?php
                $disabled = ($this->id() == self::GENERIC_ID) ? 'disabled' : false;
                echo $this->Form->input($this->model(), array(
                    'type' => 'hidden',
                    'id' => $this->gummOptionId($this->metaKey) . '.' . $this->layoutPosition . '.' . $this->id() . '.basename',
                ), array(
                    'value' => $this->name,
                    'disabled' => $disabled,
                ));
                echo $this->Form->input($this->model(), array(
                    'type' => 'hidden',
                    'id' => $this->gummOptionId($this->metaKey) . '.' . $this->layoutPosition . '.' . $this->id() . '.settings.widthRatio',
                ), array(
                    'value' => $this->widthRatio(),
                    'disabled' => $disabled,
                    'class' => 'template-element-width-ratio',
                ));
                ?>
                <div class="template-element-settings bluebox-builder-popup">
                    <a href="#" class="admin-close-button">×</a>
                    <div class="builder-popup-options">
                        <div class="options-container builder-popup-heading">
                            <h2><?php echo $this->title(); ?></h2>
                        </div>
                        <!-- <div class="builder-scrollable-content"> -->
                            <?php
                            if ($errors = $this->getErrors()) {
                                echo '<p class="not-found">' . implode('</p><p class="not-found">', $errors) . '</p>';
                            } elseif ($this->editable) {
                                $this->contentEditor();
                            }
                            ?>
                        <!-- </div> -->
                    </div>
                    <div class="options-container builder-buttons-container">
                        <button type="button" class="btn btn-large btn-primary bb-window-save"><?php _e('Save', 'gummfw'); ?></button>
                    </div>
                </div>
            
            </div>
        </div>
<?php
    }
    
    /**
     * @return void
     */
    public function contentEditor() {
        $fields = $this->fields();
        foreach ($fields as $fieldName => $fieldSettings) {
            if ($fieldName == 'widthRatio') continue;
            
            $fieldSettings = array_merge(array(
                'inputAttributes' => array(),
                'inputSettings' => array(),
            ), $fieldSettings);

?>
            <div class="options-container">
    			<div class="row-fluid">
    				<div class="span3">
                            <h5><?php echo $fieldSettings['name']; ?></h5>
                    	</div>
    				<div class="span9">
    					<div class="bb-option-row">
    					    <?php
    					    $fieldSettings['inputSettings']['label'] = false;
                            $input = $this->constructFieldInput($fieldName, $fieldSettings);
                            echo $input;
    					    ?>            
    					</div>
    				</div>
    			</div>
            </div>
<?php
        }
    }
    
    private function constructTabbedInputs($fieldSettings) {
        $tabbedInputs = array();
        foreach ($fieldSettings['tabs'] as $tabbedInput) {
            $_tabContent = '';
            if (is_string($tabbedInput)) {
                $_tabContent = $tabbedInput;
            } elseif (isset($tabbedInput['tabText'])) {
                $_tabContent = '<em>' . $tabbedInput['tabText'] . '</em>';
            } elseif (is_array($tabbedInput)) {
                foreach ($tabbedInput as $_tFieldName => $_tFieldSettings) {
                    $_tabContent .= $this->constructFieldInput($_tFieldName, $_tFieldSettings);
                }
            }
            $tabbedInputs[] = $_tabContent;
        }
        
        return $tabbedInputs;
    }
    
    protected function constructFieldInput($fieldName, $fieldSettings) {
        $fieldSettings = array_merge(array(
            'id' => $this->getFieldInputId($fieldName),
            'type' => null,
            'inputAttributes' => array(),
            'inputSettings' => array(),
            'before' => '',
            'after' => ''
        ), $fieldSettings);
        
        if (isset($fieldSettings['tabs'])) {
            $tabbedInputs = $this->constructTabbedInputs($fieldSettings);
            $fieldSettings['tabs'] = $tabbedInputs;
        }
        
        $fieldValue = '';
        if (isset($this->params[$fieldName])) $fieldValue = $this->params[$fieldName];
        elseif (isset($fieldSettings['value'])) $fieldValue = $fieldSettings['value'];
        elseif (isset($fieldSettings['default'])) $fieldValue = $fieldSettings['default'];
        
        $inputAttributes = array_merge(array(
            'class' => '',
            'value' => $fieldValue,
        ), $fieldSettings['inputAttributes']);
        
        if ($fieldSettings['type'] === 'text') {
            $inputAttributes['class'] .= ' span12';
        }
        
        $inputSettings = $fieldSettings['inputSettings'];
        
        unset($fieldSettings['inputAttributes']);
        unset($fieldSettings['inputSettings']);
        
        return $this->Form->input(
            $this->model(),
            $fieldSettings,
            $inputAttributes,
            $inputSettings
        );
    }
    
    protected function getFieldInputId($fieldName) {
        return $this->gummOptionId($this->metaKey) . '.' . $this->layoutPosition . '.' . $this->id() . '.settings.' . $fieldName;
    }
    
    // ======= //
    // SETTERS //
    // ======= //
    
    /**
     * @param string $position
     * @return void
     */
    public function setLayoutPosition($position) {
        $this->layoutPosition = $position;
    }
    
    // ======= //
    // GETTERS //
    // ======= //
    
    /**
     * @return string
     */
    public function getLayoutPosition() {
        return $this->layoutPosition;
    }
    
    /**
     * @param string $param
     * @return string
     */
    protected function getParam($param) {
        $value = '';
        if (strpos($param, '.') !== false) {
            $value = Set::classicExtract($this->params, $param);
        }
        if (!$value) {
            if (isset($this->params[$param])) {
                $value = $this->params[$param];
            } else {
                $value = Set::classicExtract($this->fields(), $param . '.value');
                if (!$value) {
                    foreach ($this->fields() as $field) {
                        if (isset($field['tabs'])) {
                            foreach ($field['tabs'] as $tab) {
                                if (isset($tab[$param]) && isset($tab[$param]['value'])) {
                                    $value = $tab[$param]['value'];
                                    break;
                                }
                            }
                        }
                        if ($value) {
                            break;
                        }
                    }
                }
            }
        }
        
        // $backtrace = debug_backtrace();
        // 
        // if (
        //     $backtrace[1]['function'] === '_render' &&
        //     $param === 'headingText'
        //     && function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')
        //     && $value
        // ) {
        //     $value = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($value);
        // }

        return $value;
    }
    
    /**
     * @param int $width
     * @return int $height
     */
    protected function getBaseDimensionsForWidth($width) {
        if (!$aspectRatio = $this->getParam('aspectRatio')) $aspectRatio = 1.77;
        
        return array('width' => $width, 'height' => round($width/$aspectRatio));
    }
    
    /**
     * @return string
     */
    private function getEditLink() {
        $link = '#';
        if ($this->editable === 'ajax') {
            $link = GummRouter::url(array('ajax' => true, 'admin' => 'true', 'controller' => 'layouts', 'action' => 'edit_layout_element', 'elementName' => $this->name, 'elementId' => $this->id(), 'postId' => $this->postId, 'model' => $this->model));
        }
        
        return $link;
    }
    
    protected function getTaxQuery() {
        $taxQuery = array();
        if ($this->supports('categories') && $categories = $this->getParam('category')) {
            $taxQuery[] = array(
                'taxonomy' => $this->postType . '_category',
                'field' => 'slug',
                'terms' => (array) $categories,
            );
        }
        
        return $taxQuery;
    }
    
    protected function queryPosts($args=array(), $params=array()) {
        global $paged, $wp_query;
        
        if ($this->posts) {
            return $this->posts;
        }
        
        $postType = ($this->supports('postType')) ? $this->getParam('postType') : $this->queryPostType;
        
        if ($postType === 'default') {
            $this->posts = $wp_query->posts;
            return $this->posts;
        }
        
        if (!$paged && isset($wp_query->query) && isset($wp_query->query['paged'])) {
            $paged = $wp_query->query['paged'];
        }
        
        $this->resetQueryAfterRender = true;
        $this->_originalQuery = clone $wp_query;
        $posts = array();
        if ($postType === 'flickr') {
            App::uses('GummFlickr', '/Vendor/Flickr');
            $Flickr = new GummFlickr();
            $posts = $Flickr->findPhotos($this->getParam('flickrUser'), array(
                'limit' => $this->getParam('postsNumber'),
            ));
            if ($posts) {
                $wp_query->posts = $posts;
                $wp_query->found_posts = count($posts);
                $wp_query->post_count = count($posts);
            }
            
        } else {
            $args = array_merge(array(
                'post_type' => $postType,
                'posts_per_page' => $this->getParam('postsNumber'),
                'paged' => $paged ? $paged : 1,
            ), $args);

            $postType = $args['post_type'];


            $termIds = (array) $this->getParam($postType . '-category');
            $termIds = Set::filter(Set::booleanize($termIds));
            if ($termIds) {
                $termIds = array_keys($termIds);
            }

            if ($termIds) {
                $termName = ($postType == 'post') ? 'category' : $postType .'_category';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $termName,
                        'field' => 'term_id',
                        'terms' => (array) $termIds
                    ),
                );
            }
            
            $args = $this->filterQueryPostsArgs($args);

            $posts = query_posts($args);
        }
        
        return $posts;
    }
    
    protected function getMediaItems() {
        if (!$this->mediaItems) {
            $media = array();
            if (!$mediaSource = $this->getParam('mediaSource')) return $media;


            switch ($mediaSource) {
             case 'post':
                $posts = $this->queryPosts();
                foreach($posts as $post) {
                    if ($post->Thumbnail) {
                        $post->Thumbnail->post_parent = $post->ID;
                        $media[] = $post->Thumbnail;
                    }
                }
                break;
             case 'custom':
             case 'media':
                $media = GummRegistry::get('Model', 'Post')->findAttachmentPosts($this->getParam('media'));
                break;
            }
            
            $this->mediaItems = $media;
        }
        
        return $this->mediaItems;
    }
    
    public function setErrors($errors=array()) {
        $this->errors = array_merge($this->errors, (array) $errors);
    }
    
    public function getErrors() {
        return (array) $this->errors;
    }
    
    protected function isPluginInstalled($setErrors=true) {
        if (!$this->supports('plugin')) return;
        
        $active = $this->Wp->isPluginActive($this->supports['plugin']['path']);
        if (!$active && $setErrors === true) {
            $this->setErrors(array(
                $this->supports['plugin']['name'] . __(' plugin is not installed. The plugin comes with this theme\'s downloaded files. Please install it to use the element.', 'gummfw')
            ));
        }
        
        return $active;
    }
    
    public function setShouldPaginate($val) {
        $this->shouldPaginate = (bool) $val;
    }
    
    /* **************** */
    /* Public Callbacks */
    
    public function initialize() {}
    
    public function beforeRender($options) {return true;}
    
    /* ******************* */
    /* Protected Callbacks */
    
    protected function filterQueryPostsArgs($args) {return $args;}
}
?>