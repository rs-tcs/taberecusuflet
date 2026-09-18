<?php
abstract class GummLayoutPostsRendererElement extends GummLayoutElement {
    /**
     * @var array
     */
    protected $supports = array(
        'title',
        'excerpt',
        'postsNumber' => 20,
        'postType' => 'post',
        // 'postColumns',
        // 'layout' => 'grid',
        'categoriesFilter',
    );
    
    protected $viewFile = 'post';
    
    private $postColumns = 1;
    
    public function beforeRender($options) {
        if ($this->getParam('layout') === 'grid') {
            $this->postColumns = (int) $this->getParam('gridColumns');
        } elseif ($this->getParam('layout') === 'slider') {
            $this->postColumns = (int) $this->getParam('sliderColumns');
        }
        $this->setParam('postColumns', $this->postColumns);
        
        $this->posts = $this->queryPosts();
        
        if ($this->getParam('layout') === 'slider' && count($this->posts) > $this->postColumns) {
            $this->shouldPaginate = true;
            $this->htmlClass .= ' gumm-layout-element-slider';
            $this->htmlElementData = array(
                'data-directional-nav' => '.heading-pagination',
                'data-num-visible' => $this->postColumns,
            );
        } elseif ($this->getParam('layout') === 'grid') {
            $this->htmlClass .= ' gumm-layout-element-grid';
        } elseif ($this->getParam('layout') === 'vCard') {
            $this->htmlClass .= ' project-wide';
        }
    }
    
    public function _render($options) {
        if ($this->getParam('layout') === 'vCard') {
            $this->_renderVCardLayout($options);
        } else {
            $columns = $this->postColumns;
            $rowSpan = 12 / $columns;
            $foundPosts = count($this->posts);

            $rowClass = array('row-fluid');
            if ($this->shouldPaginate) $rowClass[] = 'slides-container';
?>

            <div class="<?php echo implode(' ', $rowClass); ?>">
<?php
            $counter = 1;

            while (have_posts()): the_post();
                global $post;

                View::renderElement('layout-components-parts/single/staff', array(
                    'rowSpan' => $rowSpan,
                    'columns' => $columns,
                    'counter' => $counter,
                    'shouldPaginate' => $this->shouldPaginate,
                    'post' => $post,
                    'excerptLength' => $this->getParam('excerptLength'),
                    'socialNetworksDisplay' => Set::booleanize($this->getParam('socialNetworksDisplay')),
                ));

                if ($counter % $columns === 0 && $counter < $foundPosts && $this->getParam('layout') === 'grid') {
                    echo '</div><div class="row-fluid">';
                }

                $counter++;
            endwhile;
?>
            </div>
<?php
        }
    }
    
    public function _renderVCardLayout($options) {
        while (have_posts()): the_post();
            global $post;
            
            $divAtts = array(
                'class' => array(
                    'gumm-filterable-item',
                    'row-fluid',
                    'bluebox-single-staff-wrap',
                    'new-builder-element',
                ),
            );
            $categories = $this->Wp->getPostCategories($post);
            foreach ($categories as $catId => $catName) {
                $divAtts['class'][] = 'for-category-' . $catId;
            }
            echo '<div' . $this->Html->_constructTagAttributes($divAtts) . '>';
            $this->requestAction(array(
                'controller' => 'layout_elements',
                'action' => 'display',
                'single_staff_vcard',
                array(
                
                ),
                array(
                    'moreLink' => true,
                    'socialNetworksDisplay' => Set::booleanize($this->getParam('socialNetworksDisplay')),
                ),
            ));
            echo '</div>';
        endwhile;
    }
    
    public function _fields(){
        return array(
            'layout' => array(
                'name' => __('Element Layout', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'grid' => __('Grid', 'gummfw'),
                    'slider' => __('Row Slider', 'gummfw'),
                    'vCard' => __('vCard', 'gummfw'),
                ),
                'value' => 'grid',
                'tabs' => array(
                    array(
                        'gridColumns' => array(
                            'name' => __('Grid Columns', 'gummfw'),
                            'type' => 'select',
                            'value' => '4',
                            'inputOptions' => array(
                                '1'   => __('1 column', 'gummfw'),
                                '2'   => __('2 columns', 'gummfw'),
                                '3'   => __('3 columns', 'gummfw'),
                                '4'   => __('4 columns', 'gummfw'),
                                '6'   => __('6 columns', 'gummfw'),
                            ),
                        ),
                    ),
                    array(
                        'sliderColumns' => array(
                            'name' => __('Slider Columns', 'gummfw'),
                            'type' => 'select',
                            'value' => '4',
                            'inputOptions' => array(
                                '1'   => __('1 column', 'gummfw'),
                                '2'   => __('2 columns', 'gummfw'),
                                '3'   => __('3 columns', 'gummfw'),
                                '4'   => __('4 columns', 'gummfw'),
                                '6'   => __('6 columns', 'gummfw'),
                            ),
                        ),
                    ),
                    array(
                        'tabText' => __('No additional settings for this option', 'gumfw'),
                    ),
                ),
            ),
            'socialNetworksDisplay' => array(
                'name' => __('Display Social Networks', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
                'value' => 'true',
            ),
        );
    }
        
}
?>