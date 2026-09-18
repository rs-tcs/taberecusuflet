<?php
class PortfolioLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '5EC47C6C-BA41-4562-A17E-4B5DD1402FED';
    
    /**
     * @var string
     */
    public $group = 'posts';
    
    /**
     * @var int
     */
    // protected $gridColumns = 4;
    
    /**
     * @var array
     */
    protected $supports = array(
        'title',
        'postsNumber' => 20,
        'postType' => array(
            'value' => 'portfolio',
            'flickr' => false
        ),
        'excerpt'
        // 'postColumns' => array(
        //     'min' => 1,
        // ),
        // 'layout' => 'grid',
        // 'categoriesFilter',
    );
    
    // public function __construct($data=array()) {
    //     parent::__construct($data);
    //     
    //     $this->layoutsAvailable['portfolio-medium-image'] = __('Medium Image Layout', 'gummfw');
    // }
    
    /**
     * @return string
     */
    public function title() {
        return __('Portfolio Layout', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        $gridLayoutFields = array(
            'gridLayoutStyle' => array(
                'name' => __('Layout Style', 'gummfw'),
                'type' => 'select',
                'value' => 'slider',
                'inputOptions' => array(
                    '1'   => __('1 column', 'gummfw'),
                    '2'   => __('2 columns', 'gummfw'),
                    '3'   => __('3 columns', 'gummfw'),
                    '4'   => __('4 columns', 'gummfw'),
                    '6'   => __('6 columns', 'gummfw'),
                    'halfImage'   => __('Half image on the left', 'gummfw'),
                ),
            ),
            'categoriesFilter' => array(
                'name' => __('Display filterable categories', 'gummfw'),
                'type' => 'radio',
                'value' => 'false',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                )
            ),
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
        
        $sliderLayoutFields = $this->_postColumnsFields(array(
            'min' => 1,
            'max' => 6,
            'skip' => 5,
            'value' => 4
        ));
        
        return array(
            'layout' => array(
                'name' => __('Element Layout', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'grid' => __('Grid', 'gummfw'),
                    'slider' => __('Row Slider', 'gummfw'),
                ),
                'value' => 'grid',
                'tabs' => array(
                    $gridLayoutFields,
                    $sliderLayoutFields,
                ),
            ),
            'lightBoxLinkDisplay' => array(
                'name' => __('Display LightBox Link', 'gummfw'),
                'type' => 'radio',
                'value' => 'true',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
            ),
        );
        
        return $layoutFields;
    }
    
    public function beforeRender($options) {
        $this->posts = $this->queryPosts();
        
        if ($this->getParam('layout') === 'slider' && count($this->posts) > $this->getParam('postColumns')) {
            $this->shouldPaginate = true;
            $this->htmlClass .= ' gumm-layout-element-slider';
            $this->htmlElementData = array(
                'data-directional-nav' => '.heading-pagination',
                'data-num-visible' => (int) $this->getParam('postColumns'),
            );
        } elseif ($this->getParam('layout') !== 'slider') {
            $this->htmlClass .= ' gumm-layout-element-grid';
        }
        
        if ($this->getParam('layout') === 'grid' && $this->getParam('gridLayoutStyle') === 'halfImage') {
            $this->setParam('postColumns', 1);
        } elseif ($this->getParam('layout') === 'grid') {
            $this->setParam('postColumns', (int) $this->getParam('gridLayoutStyle'));
        }
    }
    
    protected function _render($options) {
        
        if ($this->getParam('layout') === 'grid' && $this->getParam('gridLayoutStyle') === 'halfImage') {
            $this->_renderMediumImageLayout();
        } else {
            $this->_renderDefaultLayout();
        }

    }
    
    public function _renderDefaultLayout() {
        $columns = (int) $this->getParam('postColumns');
        $rowSpan = 12 / $columns;

        $foundPosts = count($this->posts);

        $rowClass = array('row-fluid', 'portfolio-cols');
        if ($this->shouldPaginate) $rowClass[] = 'slides-container';

?>

        <div class="<?php echo implode(' ', $rowClass); ?>">
<?php
        $counter = 1;
        global $post;
        foreach ($this->posts as $post):
            $categories = $this->Wp->getPostCategories($post);
            $spanClass = array(
                'span' . $rowSpan,
                'gumm-filterable-item'
            );
            foreach ($categories as $catId => $catName) {
                $spanClass[] = 'for-category-' . $catId;
            }
            if ($this->shouldPaginate && $counter > $columns) $spanClass[] = 'hidden';
?>

            <div class="<?php echo implode(' ', $spanClass); ?>">
                <?php if ($post->Thumbnail): ?>
                <div class="image-wrap">
                    <div class="image-details">
                        <?php
                        echo $this->Media->display($post->Thumbnail->guid, array(
                            'ar' => 1.62068965517,
                            'context' => 'span' . $rowSpan,
                        ));
                        ?>
                        <a<?php echo $this->_imageLinkAtts(); ?>>
                        <?php
                        if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                            echo '<i class="icon-search"></i>';
                        }
                        ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <div class="project-post-details">
                    <div href="<?php the_permalink(); ?>" class="head-link"><h4><?php the_title(); ?></h4></div>
                    <?php
                    if ($this->getParam('excerptLength') > 0) {
                        echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
                    }
                    ?>
                </div>
            </div>
<?php
            if ($counter % $columns === 0 && $counter < $foundPosts && $this->getParam('layout') === 'grid') {
                echo '</div><div class="' . implode(' ', $rowClass) . '">';
            }
            $counter++;
        endforeach;
?>
        </div>
<?php

    }
    
    public function _renderMediumImageLayout() {
?>
        <div class="portfolio-loop">
            <?php while (have_posts()): the_post(); ?>
            <?php
            global $post;
            
            
            $categories = $this->Wp->getPostCategories($post);
            $divClass = array(
                'gumm-filterable-item',
                'project-line'
            );
            foreach ($categories as $catId => $catName) {
                $divClass[] = 'for-category-' . $catId;
            }
            
            ?>
            <div class="<?php echo implode(' ', $divClass); ?>">
                
                <div class="project-half">
                    <?php if ($post->Thumbnail): ?>
                    <div class="image-wrap">
                        <div class="image-details">
                            <?php
                            echo $this->Media->display($post->Thumbnail->guid, array(
                                'ar' => 1.62251655629,
                                'context' => 'span6',
                            ));
                            ?>
                            <a<?php echo $this->_imageLinkAtts(); ?>>
                            <?php
                            if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                                echo '<i class="icon-search"></i>';
                            }
                            ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="project-half">
                    <div class="half-content">
                        <h3 class="line-heading"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                        <em class="tags"><?php echo implode(' / ', $this->Wp->getPostCategories($post)); ?></em>
                        <?php
                        if ($this->getParam('excerptLength') > 0) {
                            echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
                        }
                        ?>
                        <a href="<?php the_permalink(); ?>" class="bluebox-button extra">
                            <?php _e('Read More', 'gummfw'); ?><span class="icon-chevron-right"></span>
                        </a>
                        <?php if (isset($post->PostMeta['project_link_url']) && $post->PostMeta['project_link_url']): ?>
                            <a href="#" class="bluebox-button light extra" target="_blank">
                                <?php echo $post->PostMeta['project_link_title']; ?><span class="icon-chevron-right"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php endwhile; ?>
        </div>
<?php
    }
    
    private function _imageLinkAtts() {
        global $post;
        
        $atts = array(
            'href' => get_permalink(),
            'class' => array('image-details-link'),
        );
        
        if ($this->getParam('lightBoxLinkDisplay') === 'true') {
            $atts['class'][] = 'image-wrap-mask';
            if ($post->Thumbnail) {
                $atts['href'] = $post->Thumbnail->permalink;
                $atts['rel'] = 'prettyPhoto[' . $this->htmlElementId . ']';
            }
        }
        
        return $this->Html->_constructTagAttributes($atts);
    }
}
?>