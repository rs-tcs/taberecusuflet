<?php
require_once('functions' . DS . 'gumm_woocommerce_functions.php');

class GummWooCommerce extends GummExternalPluginBase {
    
    public $wpActions = array(
        'init' => 'registerPublicScripts',
        'wp_head' => 'enqueuePublicScripts',
        'gumm_header_info_bar_after_text' => 'addCartToHeader',
    );
    
    public $wpFilters = array(
        'gumm_header_info_bar_attributes' => '_filterHeaderInfoBarAttributes',
        'gumm_header_info_bar_should_render' => '_filterHeaderInfoBarShouldRender',
    );
    
    private $prettyPhotoTargetLinks = array();
    
    private $wooCommerceDefaultWidgets = array(
        'best_sellers',
        'shopping_cart',
        'featured-products',
        'woocommerce_layered_nav_filters',
        'woocommerce_layered_nav',
        'onsale',
        'price_filter',
        'product_categories',
        'product_search',
        'product_tag_cloud',
        'woocommerce_random_products',
        'recent_products',
        'recent_reviews',
        'recently_viewed_products',
        'top-rated-products',
    );
    
    protected function initialize() {
        //this line removes the existing Woocommerce codes
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        //This line adds a new action which adds a tag BEFORE the woocommerce “stuff”
        add_action('woocommerce_before_main_content', array(&$this, '_actionBeforeMainContent'), 10);
        //This line adds a new action which adds a tag AFTER the woocommerce “stuff”
        add_action('woocommerce_after_main_content', array(&$this, '_actionAfterMainContent'), 10);
        
        add_filter('woocommerce_show_page_title', array(&$this, '_filterShowShopPageTitle'));
        add_filter('gumm_render_page_heading', array(&$this, '_filterRenderPageHeading'), 10);
        add_filter('gumm_post_cateory_term_name', array(&$this, '_filterPostCategoryTermName'), 10, 2);
        add_filter('gumm_page_subheading', array(&$this, '_fitlerPageSubheading'), 10);
        add_filter('gumm_page_title', array(&$this, '_filterPageTitle'), 10);
        add_filter('gumm_breadcrumb_path', array(&$this, '_filterBreadrcumbPath'), 10);
        add_filter('gumm_attached_media_ids', array(&$this, '_filterSetMediaIds'), 10, 2);
        add_filter('gumm_filter_sidebar_class', array(&$this, '_filterSidebarClass'), 10, 3);
        
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
        
        add_action('woocommerce_before_shop_loop_item_title', array(&$this, '_actionBeforeLoopThumbnail'), 9);
        add_action('woocommerce_before_shop_loop_item_title', array(&$this, '_actionAfterLoopThumbnail'), 11);
        
        add_filter('woocommerce_sale_flash', array(&$this, '_filterSaleBadge'), 10);
        add_filter('woocommerce_single_product_image_html', array(&$this, '_filterSingleProductImage'), 10, 2);
        add_filter('woocommerce_single_product_image_thumbnail_html', array(&$this, '_filterSingleProductThumbnailImage'), 10);
        add_action('woocommerce_after_cart', array(&$this, '_actionAfterCart'));
        
        // Used to populate the menu cart item
		add_filter('add_to_cart_fragments', array( &$this, '_filterAddToCartFragment' ) );
        
        add_action('gumm_woocommerce_print_pretty_photo_hidden_links', array(&$this, '_actionPrintPrettyPhotoHiddenLinks'));
        
	    add_action( 'woocommerce_product_thumbnails', array(&$this, '_filterAfterSingleProductThumbnails'), 21 );	    
        
        // remove as we're using ours
    	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        
        Configure::write('Data.BuilderElements.shop.elements', array(
            'gumm_woocommerce_products',
        ));
        
        $styles = Configure::read('css.public');
        $styles[] = array(
            'url' => array(
                'bootstrapSelect' => GUMM_THEME_CSS_URL . 'bootstrap-select.min.css'
            ),
        );
        Configure::write('css.public', $styles);
        
        $this->addThemeOptions();
        
        if (!is_admin()) {
            $this->printJs();
        }
        
        // And add the theme support
        add_theme_support('woocommerce');
    }
    
    public function _actionBeforeLoopThumbnail(){
        global $post;
?>
        <div class="image-wrap">
            <div class="image-details">
<?php
    }
    
    public function _actionAfterLoopThumbnail() {
        global $post;
        
        if ($post->GummOption['lightboxLinksDisplay'] !== 'none') {
            $prettyPhotoLinkId = 'gumm_pp_' . uniqid();
            $atts = array(
                'id' => $prettyPhotoLinkId,
                'href'  => $post->Thumbnail->permalink,
                // 'class' => array('image-details-link', 'image-wrap-mask'),
                'style' => 'display:none;',
                'rel'   => $post->GummOption['lightboxLinksDisplay'] === 'same' ? 'prettyPhoto[gumm_wooc_' . $post->ID . ']' : 'prettyPhoto[gumm_wooc]',
            );
            
            echo '<span class="image-details-link image-wrap-mask" data-target-link=#' . $prettyPhotoLinkId . '><i class="icon-search"></i></span>';
            
            $this->prettyPhotoTargetLinks[] = '<a' . $this->Wp->_constructTagAttributes($atts) . '></a>';
            // echo '<a' . $this->Wp->_constructTagAttributes($atts) . '><i class="icon-search"></i></a>';
            if ($post->GummOption['lightboxLinksDisplay'] === 'same' && count($post->Media) > 1) {
                foreach ($post->Media as $postMedia) {
                    $this->prettyPhotoTargetLinks[] = '<a href="' . $postMedia->permalink . '" rel="prettyPhoto[gumm_wooc_' . $post->ID . ']" style="display:none;"></a>';
                }
            }
        }
?>
            
            </div>
        </div>
<?php
        
    }
    
    public function _actionBeforeMainContent() {
        echo '<div class="row-fluid bluebox-container"><div class="bluebox-builder-row">';
    }
    
    public function _actionAfterMainContent() {
        echo '</div></div>';
        
        do_action('gumm_woocommerce_print_pretty_photo_hidden_links');
    }
    
    public function _actionAfterCart() {
        do_action('gumm_woocommerce_print_pretty_photo_hidden_links');
    }
    
    public function _actionPrintPrettyPhotoHiddenLinks() {
        foreach ($this->prettyPhotoTargetLinks as $ppTargetLink) {
            echo $ppTargetLink;
        }
    }
    
    public function _filterSingleProductImage($outputHtml, $postId) {
        $zoomSpanHtml   = '<span class="image-details-link image-wrap-mask no-prevent-default"><i class="icon-search"></i></span>';
        
        $outputHtml     = '<div class="image-wrap image-wrap-single-product"><div class="image-details">' . $outputHtml;
        $outputHtml     = str_replace('</a>', $zoomSpanHtml . '</a>', $outputHtml);
        $outputHtml     = $outputHtml . '</div></div>';
        
        $outputHtml     .= '<div class="row-fluid">';
        
        return $outputHtml;
    }
    
    public function _filterSingleProductThumbnailImage($outputHtml) {
        $columns = (int) apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
        $rowSpan = 12/$columns;
        
        $zoomSpanHtml   = '<span class="image-details-link image-wrap-mask no-prevent-default"><i class="icon-search"></i></span>';
        $outputHtml     = str_replace('</a>', $zoomSpanHtml . '</a>', $outputHtml);
        
        return '<div class="image-wrap span' . $rowSpan . '"><div class="image-details">' . $outputHtml . '</div></div>';
    }
    
    public function _filterAfterSingleProductThumbnails() {
        echo '</div>';
    }
    
    public function _filterShowShopPageTitle() {
        return false;
    }
    
    public function _filterSaleBadge($outputHtml) {
        
        return '<div class="ribbon-container">
                    <div class="ribbon-pro">
                        <div class="content">' . __('sale', 'gummfw') . '</div>
                        <div class="back-sh"></div>
                        <div class="back-sh-2"></div>
                    </div>
                </div>';
    }
    
    public function _filterPostCategoryTermName($termName, $postType) {
        if ($postType === 'product') {
            $termName = 'product_cat';
        }
        
        return $termName;
    }
    
    public function _filterRenderPageHeading($render) {
        if (is_shop()) {
            $render = true;
        }
        
        return $render;
    }
    
    public function _fitlerPageSubheading($subHeading) {
        if (function_exists('is_product') && is_product()) {
            global $post, $product;
            
            if (!is_a($product, 'WC_Product_Simple')) {
                $product = get_product($post);
            }
            $product = get_product($post);
            // d($product);
            $subHeading = $product->get_price_html();
        }
        return $subHeading;
    }
    
    public function _filterPageTitle($pageTitle) {
        if (function_exists('is_shop') && is_shop()) {
            $pageTitle = $this->getShopPageTitle();
        }
        
        return $pageTitle;
    }
    
    public function _filterBreadrcumbPath($path) {
        if (is_shop()) {
            $path[] = array(
                'id' => null,
                'title' => $this->getShopPageTitle(),
                'url' => '',
            );
        }
        return $path;
    }
    
    public function _filterSetMediaIds($ids, $post) {
        if ($post->post_type === 'product') {
            $product = get_product($post);
            $ids = $product->get_gallery_attachment_ids();
        }
        
        return $ids;
    }
    
    public function _filterHeaderInfoBarShouldRender($shouldRender) {
        if (!$shouldRender) {
            $shouldRender = $this->displayCart();
        }
        
        return $shouldRender;
    }
    
    public function _filterHeaderInfoBarAttributes($attributes) {
        global $woocommerce;
		$cartContentsCount = $woocommerce->cart->cart_contents_count;
		
		if ($this->displayCart()) {
		    $attributes['class'][] = 'has-cart';
		}
        
        return $attributes;
    }
    
    public function _filterSidebarClass($class, $sidebarId, $orientation) {
        if (!is_shop()) {
            $sidebars = wp_get_sidebars_widgets();
            $isWooCommerceSidebar = false;
            if (isset($sidebars[$sidebarId]) && $sidebars[$sidebarId]) {
                foreach ($sidebars[$sidebarId] as $widgetForSidebar) {
                    if ($isWooCommerceSidebar) {
                        break;
                    }
                    foreach ($this->wooCommerceDefaultWidgets as $woocWidget) {
                        if (strpos($widgetForSidebar, $woocWidget) === 0) {
                            $isWooCommerceSidebar = true;
                            break;
                        }
                    }

                }
            }
            
            if ($isWooCommerceSidebar) {
                $class .= ' woocommerce-page';
            }
        }
        
        return $class;
    }
    
    // public function _filterAddCartToMenu($items, $args) {
    //     $displayCart = $this->Wp->getOption('gumm_woocommerce.menu_cart_display');
    //     if (
    //         is_object($args->walker) &&
    //         get_class($args->walker) === 'GummNavMenuWalker' &&
    //         $args->theme_location === 'prime_nav_menu' &&
    //         $this->Wp->getOption('gumm_woocommerce.show_menu_cart') === 'true'
    //     ) {
    //         if ($menuCartItem = $this->menuCartItem()) {
    //             $items .= '<li class="gumm-woocommerce-menu-cart-item"><a id="gumm-woocommerce-menu-cart-button" href="#"><i class="icon-shopping-cart"></i></a>' . $menuCartItem . '</li>';
    //         }
    //     }
    //     
    //     return $items;
    // }
    // 
    // public function _filterAddCartToMenuPages($items) {
    //     if ($menuCartItem = $this->menuCartItem()) {
    //         $items .= '<li class="gumm-woocommerce-menu-cart-item"><a id="gumm-woocommerce-menu-cart-button" href="#"><i class="icon-shopping-cart"></i></a>' . $menuCartItem . '</li>';
    //     }
    //     
    //     return $items;
    // }
    
    public function addCartToHeader() {
        if (!$this->displayCart()) {
            return '';
        }
        global $woocommerce;
?>
        <div id="gumm-woocommerce-header-info-cart" class="info-cart">
            <a id="gumm-woocommerce-menu-cart-button" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
                <i class="icon-shopping-cart"></i>
                <?php _e('Checkout', 'gummfw'); ?>
            </a>
            <?php echo $this->menuCartItem(); ?>
        </div>
<?php
    }
    
    public function _filterAddToCartFragment($fragments) {
		$fragments['div.gumm-woocommerce-cart-contents'] = $this->menuCartItem();
		return $fragments;
    }
    
    public function menuCartItem() {
		global $woocommerce;
	
	    $menuItem = false;
	    
		$viewingCart = __('View your shopping cart', 'wcmenucart');
		$startShopping = __('Start shopping', 'wcmenucart');
		$cartUrl = $woocommerce->cart->get_cart_url();
		$shopPageUrl = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$cartContentsCount = $woocommerce->cart->cart_contents_count;
		$cartContents = sprintf(_n('%d item', '%d items', $cartContentsCount, 'wcmenucart'), $cartContentsCount);
		$cartTotal = $woocommerce->cart->get_cart_total();
		
		$itemDisplay = $this->Wp->getOption('gumm_woocommerce.menu_cart_display');
	
		if ($cartContentsCount > 0 || $this->Wp->getOption('gumm_woocommerce.menu_cart_always_display', array('booleanize' => true))) {
			if ($cartContentsCount == 0) {
                // $menuItem = '<a class="gumm-woocommerce-cart-contents" href="'. $shopPageUrl .'" title="'. $startShopping .'">';
			} else {
                // $menuItem = '<a class="gumm-woocommerce-cart-contents" href="'. $cartUrl .'" title="'. $viewingCart .'">';
			}
			$menuItem = '<div class="gumm-woocommerce-cart-contents">';
			
            // $menuItem .= '<i class="icon-shopping-cart"></i>';

			switch ($itemDisplay) {
				case 'items': //items only
					$menuItem .= $cartContents;
					break;
				case 'price': //price only
					$menuItem .= $cartTotal;
					break;
				case 'all': //items & price
					$menuItem .= $cartContents.' - '. $cartTotal;
					break;
			}
			$menuItem .= '</div>';
		}
		
		return $menuItem;
    }
    
    private function displayCart() {
        global $woocommerce;
        
		$cartContentsCount = $woocommerce->cart->cart_contents_count;
		$shouldDisplayCart = $this->Wp->getOption('gumm_woocommerce.show_menu_cart');
		
		if ($shouldDisplayCart === 'true') {
            return $cartContentsCount > 0 || $this->Wp->getOption('gumm_woocommerce.menu_cart_always_display', array('booleanize' => true));
		} else {
		    return false;
		}
    }
    
    private function getShopPageTitle() {
        ob_start();
        woocommerce_page_title();
        return ob_get_clean();
    }
    
    private function addThemeOptions() {
        Configure::write('admin.options.tabs.woocommerce_tab', array(
    		'id' => 'woocommerce_tab',
    		'title' => __('WooCommerce', 'gummfw'),
    		'toolbar' => '',
    		'parent_id' => '',
        ));
        
        $adminOptions = Configure::read('admin.options.options');
        
        // The Layout Options
        $adminOptions[] = array(
            'name' => __('WooCommerce Products Pages Layout Structure', 'gummfw'),
            'description' => __('Use the manager to adjust your layout.', 'gummfw'),
            'important' => '',
            'id' => GUMM_THEME_PREFIX . '_layout.woocommerce_shop.schema',
            'default' => 'c-r',
            'tab_id' => 'woocommerce_tab',
            'group' => 'layout',
            'type' => 'layouts',
            'data' => '',
            'inputOptions' => '',
            'inputAttribtues' => '',
            'inputSettings' => '',
            'dependant' => GUMM_THEME_PREFIX . '_layout.woocommerce_shop.sidebars',
            'dependsOn' => '',
            'requestAction' => array(
                'controller' => 'Layouts', 
                'action' => 'admin_edit', 
            ),
            'model' => 'Option',
            'inputAttributes' => '',
        );
        $adminOptions[] = array(
        	'name' => __('WooCommerce Products Pages Sidebars Content', 'gummfw'),
        	'description' => __('Use the manager to adjust your sidebars content. Drag and drop the sidebars on the left to their appropriate positions in the layout preview.', 'gummfw'),
        	'important' => '',
        	'id' => GUMM_THEME_PREFIX . '_layout.woocommerce_shop.sidebars',
        	'default' => '',
        	'tab_id' => 'woocommerce_tab',
        	'group' => 'layout',
        	'type' => 'layouts',
        	'data' => '',
        	'inputOptions' => '',
        	'inputAttribtues' => '',
        	'inputSettings' => '',
        	'dependant' => '',
        	'dependsOn' => GUMM_THEME_PREFIX . '_layout.woocommerce_shop.schema',
        	'requestAction' => array(
                'controller' => 'Layouts', 
                'action' => 'admin_edit_layout_sidebars', 
            ),
        	'adminActions' => array(
                'save' => '#', 
            ),
        	'model' => 'Option',
        	'inputAttributes' => '',
        );
        
        // SingleProductLayoutOptions
        $adminOptions[] = array(
            'name' => __('WooCommerce Single Product Page Layout Structure', 'gummfw'),
            'description' => __('Use the manager to adjust your layout.', 'gummfw'),
            'important' => '',
            'id' => GUMM_THEME_PREFIX . '_layout.product-post.schema',
            'default' => 'c-r',
            'tab_id' => 'woocommerce_tab',
            'group' => 'single_layout',
            'type' => 'layouts',
            'data' => '',
            'inputOptions' => '',
            'inputAttribtues' => '',
            'inputSettings' => '',
            'dependant' => GUMM_THEME_PREFIX . '_layout.product-post.sidebars',
            'dependsOn' => '',
            'requestAction' => array(
                'controller' => 'Layouts', 
                'action' => 'admin_edit', 
            ),
            'model' => 'Option',
            'inputAttributes' => '',
        );
        $adminOptions[] = array(
        	'name' => __('WooCommerce Single Product Page Sidebars Content', 'gummfw'),
        	'description' => __('Use the manager to adjust your sidebars content. Drag and drop the sidebars on the left to their appropriate positions in the layout preview.', 'gummfw'),
        	'important' => '',
        	'id' => GUMM_THEME_PREFIX . '_layout.product-post.sidebars',
        	'default' => '',
        	'tab_id' => 'woocommerce_tab',
        	'group' => 'single_layout',
        	'type' => 'layouts',
        	'data' => '',
        	'inputOptions' => '',
        	'inputAttribtues' => '',
        	'inputSettings' => '',
        	'dependant' => '',
        	'dependsOn' => GUMM_THEME_PREFIX . '_layout.product-post.schema',
        	'requestAction' => array(
                'controller' => 'Layouts', 
                'action' => 'admin_edit_layout_sidebars', 
            ),
        	'adminActions' => array(
                'save' => '#', 
            ),
        	'model' => 'Option',
        	'inputAttributes' => '',
        );
        
        // Home Generic Options
        $adminOptions[] = array(
        	'name' => __('WooCommerce LightBox links in the loop', 'gummfw'),
        	'description' => __('Choose the display and functionality of lightbox links.', 'gummfw'),
        	'important' => '',
        	'id' => GUMM_THEME_PREFIX . '_product.lightboxLinksDisplay',
        	'default' => 'siblings',
        	'tab_id' => 'woocommerce_tab',
        	'group' => 'home',
        	'type' => 'select',
        	'data' => '',
        	'inputOptions' => array(
        	   'siblings'   => __('Show thumbnails from all products on page', 'gummfw'),
        	   'same'       => __('Show thumbnails from the single product gallery', 'gummfw'),
        	   'none'       => __('Do not display lightbox links', 'gummfw'),
        	),
        	'inputAttribtues' => '',
        	'inputSettings' => '',
        	'dependant' => '',
        	'dependsOn' => '',
        	'requestAction' => '',
        	'adminActions' => array(
                'save' => '#', 
            ),
        	'model' => 'Option',
        	'inputAttributes' => '',
        );
        $adminOptions[] = array(
        	'name' => __('WooCommerce cart display in header', 'gummfw'),
        	'description' => __('Choose whether to display cart in custom menu.', 'gummfw'),
        	'important' => '',
        	'id' => GUMM_THEME_PREFIX . '_gumm_woocommerce.show_menu_cart',
        	'default' => 'true',
        	'tab_id' => 'woocommerce_tab',
        	'group' => 'home',
        	'type' => 'tabbed-input',
        	'data' => '',
        	'inputOptions' => array(
        	   'true'       => __('Enable', 'gummfw'),
        	   'false'      => __('Disable', 'gummfw'),
        	),
        	'tabs' => array(
        	    array(
                    array(
                        'id' => GUMM_THEME_PREFIX. '_gumm_woocommerce.menu_cart_display',
                        'name' => __('Info to display', 'gummfw'),
                        'default' => 'all',
                        'type' => 'select',
                        'inputOptions' => array(
                           'price'      => __('Total price only', 'gummfw'),
                           'items'      => __('Items only', 'gummfw'),
                           'all'        => __('Items and total price', 'gummfw'),
                        ),
                    ),
                    array(
                        'id' => GUMM_THEME_PREFIX . '_gumm_woocommerce.menu_cart_always_display',
        	            'name' => __('Always display cart', 'gummfw'),
        	            'default' => 'true',
        	            'type' => 'radio',
                    	'inputOptions' => array(
                    	   'true'       => __('Enable', 'gummfw'),
                    	   'false'      => __('Disable', 'gummfw'),
                    	),
                    ),
        	    ),
        	    array(
                    'text' => __('No additional settings for this option.', 'gummfw'),
        	    ),
        	),
        	'adminActions' => array(
                'save' => '#', 
            ),
        	'model' => 'Option',
        );
        
        Configure::write('admin.options.options', $adminOptions);
    }
    
    public function registerPublicScripts() {
    }
    
    public function enqueuePublicScripts() {
    }
    
    private function printJs() {
        $this->scriptBlockStart();
?>
        $('span.image-details-link:not(.no-prevent-default)').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            $($(this).data('targetLink')).trigger('click');
        });
        $('#gumm-woocommerce-header-info-cart').on('mouseenter', function(e){
            e.preventDefault();
            var theCartContents = $(this).children('.gumm-woocommerce-cart-contents');
            
            theCartContents.show();
            $(this).data('gummCartState', 'open');
            
        });
        $('#gumm-woocommerce-header-info-cart').on('mouseleave', function(e){
            e.preventDefault();
            var theCartContents = $(this).children('.gumm-woocommerce-cart-contents');
            
            theCartContents.hide();
            $(this).data('gummCartState', 'closed');
            
        });
<?php
        $this->scriptBlockEnd();
    }
}
?>