<?php
class GalleryLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'B2571A6C-C16F-4C6D-B5C9-CD3DD147C52E';
    
    /**
     * @var string
     */
    public $group = 'posts';
    
    /**
     * @var int
     */
    protected $gridColumns = 1;
    
    /**
     * @var array
     */
     protected $supports = array(
         'title',
         'postsNumber' => 20,
         'postType' => 'gallery',
         'postColumns' => array(
             'min' => 2,
             'max' => 4,
             'value' => 3
         ),
         'layout' => 'grid',
         'categoriesFilter',
     );
    
    /**
     * @return string
     */
    public function title() {
        return __('Gallery Posts', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'enableLoadMore' => array(
                'name' => __('Display load more button if more galleries available', 'gummfw'),
                'type' => 'checkbox',
                'value' => 'true',
            ),
        );
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
        } elseif ($this->getParam('layout') === 'grid') {
            $this->htmlClass .= ' gumm-layout-element-grid';
        }
    }
    
    /**
     * @return void
     */
    protected function _render($options) {
        $columns = (int) $this->getParam('postColumns');
        $rowSpan = 12 / $columns;
        $foundPosts = count($this->posts);
        
        $rowClass = array('row-fluid');
        if ($this->shouldPaginate) $rowClass[] = 'slides-container';
        
        $filterableItems = false;
        if ($this->getParam('layout') == 'grid' && $this->getParam('categoriesFilter')) {
            View::renderElement('layout-components-parts/categories-list', array('postType' => $this->getParam('postType')));
            $filterableItems = true;
        }
        
        if ($filterableItems) {
            echo '<div class="gumm-filterable-items" data-columns="' . $columns . '">';
        }
            
        echo '<div class="' . implode(' ', $rowClass) . '">';
        // Loop the shit out of it
        $counter = 1;
        global $post;
        foreach ($this->posts as $post):
        // while (have_posts()): the_post();
            global $post;
            
            $spanClass = array(
                'span' . $rowSpan,
                'roki-portfolio-thumbs',
                'gumm-filterable-item',
            );
            if ($this->shouldPaginate && $counter > $columns) $spanClass[] = 'hidden';
            
            $categories = $this->Wp->getPostCategories($post);
            foreach ($categories as $catId => $catName) {
                $spanClass[] = 'for-category-' . $catId;
            }
?>
            <div class="<?php echo implode(' ', $spanClass); ?>">
            	<div class="gallery-wrap preloading-image-holder">
            		<div class="image-container image-wrap">
            		    <?php if ($post->Thumbnail): ?>
            		    <a href="<?php the_permalink(); ?>">
                        <?php echo $this->Media->display($post->Thumbnail->guid, array('ar' => 1.33678756477 , 'context' => 'span' . $rowSpan), array('alt' => get_the_title())); ?>
            			<span class="image-detail"></span>
            		    </a>
            		    <?php
                        View::renderElement('layout-components-parts/thumb-links', array(
                                                    'permalink' => get_permalink($post->ID),
                                                    'lightBoxLink' => $post->Thumbnail->permalink,
                                                    'rel' => $this->htmlElementId
                        ));
            		    ?>
            		    <?php endif; ?>
            		    <h4>
            		        <a class="image-title" href="<?php the_permalink(); ?>">
            		            <span class="bullet-detail"></span><?php the_title(); ?>
            		        </a>
            		    </h4>
            		</div>
                    <div class="bg-sheet-2"></div>
                    <div class="bg-sheet-3"></div>
                </div>
            </div>
<?php
            
            if ($counter % $columns === 0 && $counter < $foundPosts && $this->getParam('layout') === 'grid') {
                echo '</div><div class="row-fluid">';
            }
            
            $counter++;
        // endwhile;
        endforeach;
        
        if ($filterableItems) {
            echo '</div>';
        }
        
        echo '</div>';
    }

}
?>