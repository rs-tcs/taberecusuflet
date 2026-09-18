<?php
class SingleRelatedLayoutElement extends GummLayoutElement {
    protected $id = '79C99143-BE8B-4EFD-A2B4-EBC0BE98A4EC';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array(
        'postsNumber' => 10,
        'excerpt' => 100,
    );
    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Related Posts', 'gummfw');
    }
    
    private $metaFields;
    
    protected function _fields() {
        return array(
            'layout' => array(
                'name' => __('Element Layout', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'blog' => __('Blog Vertical Layout', 'gummfw'),
                    'portfolio' => __('Portfolio Horizontal Layout', 'gummfw'),
                ),
                'value' => 'blog',
                'tabs' => array(
                    array(
                        'rows' => array(
                            'name' => __('Number of rows', 'gummfw'),
                            'type' => 'select',
                            'inputOptions' => array(
                                '1' => 'One ' . __('row', 'gummfw'),
                                '2' => 'Two ' . __('rows', 'gummfw'),
                                '3' => 'Three ' . __('rows', 'gummfw'),
                                '4' => 'Four ' . __('rows', 'gummfw'),
                                '5' => 'Five ' . __('rows', 'gummfw'),
                                '6' => 'Six ' . __('rows', 'gummfw'),
                            ),
                            'value' => '3',
                        ),
                        'metaFields' => array(
                            'type' => 'checkboxes',
                            'name' => __('Meta fields', 'gummfw'),
                            'inputOptions' => array(
                                'author' => __('Author', 'gummfw'),
                                'date' => __('Date', 'gummfw'),
                                'categories' => __('Categories', 'gummfw'),
                                'comments' => __('Comments', 'gummfw'),
                            ),
                            'value' => array(
                                'author' => 'false',
                                'date' => 'true',
                                'categories' => 'false',
                                'comments' => 'true',
                            ),
                        ),
                        'flexSlideAnimation' => array(
                            'name' => __('Animation to use if the element should render as slider', 'gummfw'),
                            'type' => 'radio',
                            'inputOptions' => array(
                                'fade' => __('Fade', 'gummfw'),
                                'slide' => __('Slide', 'gummfw'),
                            ),
                            'value' => 'fade',
                        ),
                    ),
                    array(
                        'columns' => array(
                            'name' => __('Number of columns', 'gummfw'),
                            'type' => 'select',
                            'inputOptions' => array(
                                '2' => 'Two ' . __('columns', 'gummfw'),
                                '3' => 'Three ' . __('columns', 'gummfw'),
                                '4' => 'Four ' . __('columns', 'gummfw'),
                                '6' => 'Six ' . __('columns', 'gummfw'),
                            ),
                            'value' => '3',
                        ),
                    ),
                ),
            ),
        );
    }
    
    public function beforeRender($options) {
        global $post;
        $this->posts = GummRegistry::get('Model', 'Post')->findRelated($post, (int) $this->getParam('postsNumber'));
        
        if (!$this->posts) return false;
        
        $visibleNum = $this->getParam('layout') === 'blog' ? (int) $this->getParam('rows') : (int) $this->getParam('columns');
        if (count($this->posts) > $visibleNum) {
            if ($this->getParam('layout') === 'blog') {
                $this->shouldPaginate = true;
                $this->htmlClass .= 'flex-slider';
                $this->htmlElementData = array(
                    'data-direction-nav-container' => '#' . $this->id() . '-nav-controls',
                    'data-animation' => $this->getParam('flexSliderAnimation'),
                    'data-animation-loop' => '1',
                    'data-smooth-height' => '1',
                );
                
            } else {
                $this->shouldPaginate = true;
                $this->htmlClass .= ' gumm-layout-element-slider';
                $this->htmlElementData = array(
                    'data-directional-nav' => '.heading-pagination',
                    'data-num-visible' => (int) $this->getParam('columns'),
                );
            }
        }
        
        $this->supports['title'] = __('Related', 'gummfw');
        $this->htmlClass .= ' bluebox-related-blog-posts';
        
        $this->metaFields = array_keys(Set::filter(Set::booleanize($this->getParam('metaFields'))));
    }
    
    protected function _render($options) {
        switch ($this->getParam('layout')) {
            case 'blog':
                $this->_renderBlogVerticalLayout();
                break;
            case 'portfolio':
                $this->_renderPortfolioHorizontalLayout();
                break;
        }
        
        wp_reset_query();
    }
    
    private function _renderBlogVerticalLayout() {
        echo '<div class="slides">';
        $postBatches = array_chunk($this->posts, (int) $this->getParam('rows'));
        $counter = 0;
        foreach ($postBatches as $posts) {
            $divAtts = array(
                'class' => 'slide-item',
            );
            if ($counter > 0) {
                // $divAtts['style'] = 'display:none;';
            }
            
            echo '<div' . $this->Html->_constructTagAttributes($divAtts) . '>';
            $this->_renderBlogVerticalBatch($posts);
            echo '</div>';
            
            $counter++;
        }
        echo '</div>';
    }
    
    private function _renderBlogVerticalBatch($posts) {
        global $post;
        foreach ($posts as $post) {
            setup_postdata( $post );
            $permalink = get_permalink();
            $title = get_the_title();
            echo '<div class="row-fluid">';
            $contentSpan = 'span12';
            if ($post->Thumbnail) {
                $contentSpan = 'span8';
                echo '<div class="span4">';
                    echo '<div class="image-wrap">';
                        echo '<a href="' . $permalink . '" class="image-details">';
                            echo $this->Media->display($post->Thumbnail->guid, array(
                                'ar' => 1.61832061069,
                                'context' => 'span4'
                            ));
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            }
            echo '<div class="' . $contentSpan . '">';
                echo '<a href="' . $permalink . '" class="head-link"><h4>' . $title . '</h4></a>';
                
                if ($this->metaFields) {
                    echo '<span class="bluebox-date">';
                    $meta = $this->Html->postDetails($this->metaFields, array(
                        'prefixes' => array(
                            'author' => __('By', 'gummfw'),
                            'date' => __(' / ', 'gummfw'),
                            'comments' => __(' / ', 'gummfw'),
                            'category' => __(' / ', 'gummfw'),
                        ),
                        'beforeDetail' => '',
                        'afterDetail' => '',
                        'formats' => array(
                            'date' => 'd F Y',
                        ),
                    ));
                    
                    $meta = trim($meta, ' / ');
                    echo $meta;
                    echo '</span>';
                }
                if ($this->getParam('excerptLength') > 0) {
                    echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
                }
                echo '<a class="bluebox-more-link" href="' . $permalink . '">' . __('Read more', 'gummfw') . '<span class="icon-chevron-right"></span></a>';
                
            echo '</div>';
            
            echo '</div>';
        }
    }
    
    private function _renderPortfolioHorizontalLayout() {
        $settings = array(
            'posts' => $this->posts,
            'gridLayoutStyle' => $this->getParam('columns'),
            'layout' => 'slider',
            'postColumns' => $this->getParam('columns'),
            'excerptLength' => $this->getParam('excerptLength'),
        );
        App::import('layoutelement', 'Portfolio');
        $PortfolioElement = new PortfolioLayoutElement(array('settings' => $settings));
        $PortfolioElement->setShouldPaginate($this->shouldPaginate);
        $PortfolioElement->_renderDefaultLayout();
    }
}
?>