<?php
class GummWoocommerceProductsLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '64B7233B-A502-462E-A3D0-2238F47573E0';
    
    /**
     * @var string
     */
    public $group = 'shop';
    
    /**
     * @var string
     */
    protected $queryPostType = 'product';
    
    /**
     * @var array
     */
    protected $supports = array(
        'title',
        'postsNumber',
        'postColumns' => array(
            'min' => 2,
            'max' => 6,
            'skip' => 5,
        ),
        // 'paginationLinks',
        // 'layout',
    );
    
    public function title() {
        return __('WooCommerce Products', 'gummfw');
    }
    
    protected function _fields() {
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
                    array(
                        'foundResultsDisplay' => array(
                            'name' => __('Display found results count', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'true',
                        ),
                        'sortingDisplay' => array(
                            'name' => __('Display sorting field', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'true'
                        ),
                        'enablePaginate' => array(
                            'name' => __('Display pagination links', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'true',
                        ),
                        'orderby' => array(
                            'name' => __('Default products ordering', 'gummfw'),
                            'type' => 'select',
                            'value' => 'menu_order',
                            'inputOptions' => array(
                                'menu_order' => __( 'Default sorting', 'woocommerce' ),
                                'popularity' => __( 'Sort by popularity', 'woocommerce' ),
                                'rating'     => __( 'Sort by average rating', 'woocommerce' ),
                                'date'       => __( 'Sort by newness', 'woocommerce' ),
                                'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
                                'price-desc' => __( 'Sort by price: high to low', 'woocommerce' )
                            ),
                        ),
                    ),
                    array(
                        'tabText' => __('Sliding product carousel will be displayed', 'gummfw'),
                    ),
                ),
            ),
        );
    }
    
    protected function filterQueryPostsArgs($args) {
        global $woocommerce;
        
        if ($_args = $woocommerce->query->get_catalog_ordering_args()) {
            $args = array_merge($args, $_args);
        }
        
        return $args;
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
            $this->setParam('enablePaginate', 'false');
        } elseif ($this->getParam('layout') !== 'slider') {
            $this->htmlClass .= ' gumm-layout-element-grid';
        }
        
        $this->htmlClass .= ' woocommerce';
    }
    
    protected function _render($options) {
        global $woocommerce;
        $columns = (int) $this->getParam('postColumns');
        $rowSpan = 12 / $columns;

        $foundPosts = count($this->posts);

        $rowClass = array('row-fluid', 'products');
        if ($this->shouldPaginate) $rowClass[] = 'slides-container';
        
        $counter = 1;

        if ($this->getParam('layout') === 'grid' && $this->getParam('foundResultsDisplay') === 'true') {
            woocommerce_result_count();
        }
        if ($this->getParam('layout') === 'grid' && $this->getParam('sortingDisplay') === 'true') {
            woocommerce_catalog_ordering();
        }
?>
        <div class="<?php echo implode(' ', $rowClass); ?>">		
		<?php while ( have_posts() ) : the_post(); ?>
		    <?php
            $spanClass = array(
                'span' . $rowSpan,
                'gumm-filterable-item'
            );
            if ($this->shouldPaginate && $counter > $columns) $spanClass[] = 'hidden';
		    ?>
            <div <?php post_class(implode(' ', $spanClass)); ?>>
                
            	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

            	<a href="<?php the_permalink(); ?>">

            		<?php
            			/**
            			 * woocommerce_before_shop_loop_item_title hook
            			 *
            			 * @hooked woocommerce_show_product_loop_sale_flash - 10
            			 * @hooked woocommerce_template_loop_product_thumbnail - 10
            			 */
            			do_action( 'woocommerce_before_shop_loop_item_title' );
            		?>

            		<h3><?php the_title(); ?></h3>

            		<?php
            			/**
            			 * woocommerce_after_shop_loop_item_title hook
            			 *
            			 * @hooked woocommerce_template_loop_price - 10
            			 */
            			do_action( 'woocommerce_after_shop_loop_item_title' );
            		?>

            	</a>

            	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
			
            </div>
<?php
            if ($counter % $columns === 0 && $counter < $foundPosts && $this->getParam('layout') === 'grid') {
                echo '</div><div class="' . implode(' ', $rowClass) . '">';
            }
            $counter++;
?>
		<?php endwhile; // end of the loop. ?>
		
		</div>

<?php
        do_action('gumm_woocommerce_print_pretty_photo_hidden_links');
    }
}
?>