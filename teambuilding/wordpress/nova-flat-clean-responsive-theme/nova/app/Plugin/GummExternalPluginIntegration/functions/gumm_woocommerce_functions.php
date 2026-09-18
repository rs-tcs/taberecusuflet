<?php
if ( ! function_exists( 'woocommerce_template_single_meta' ) ) {

	/**
	 * Output the product meta.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function woocommerce_template_single_meta() {
        global $post, $product;
?>
        <div class="product_meta">

        	<?php do_action( 'woocommerce_product_meta_start' ); ?>

        	<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
        		<span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo $product->get_sku(); ?></span>.</span>
        	<?php endif; ?>

        	<?php
        		$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
        		echo $product->get_categories( ' ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $size, 'woocommerce' ) . ' ', '</span>' );
        	?>

        	<?php
        		$size = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
        		echo $product->get_tags( ' ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $size, 'woocommerce' ) . ' ', '</span>' );
        	?>

        	<?php do_action( 'woocommerce_product_meta_end' ); ?>

        </div>
<?php
	}
}

if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {

	/**
	 * Output the product sorting options.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function woocommerce_catalog_ordering() {
		global $woocommerce;

		$orderby = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

        /**
         * Show options for ordering
         *
         * @author 		WooThemes
         * @package 	WooCommerce/Templates
         * @version     2.0.0
         */

        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

        global $woocommerce, $wp_query;

        if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
        	return;
        ?>
        <form class="woocommerce-ordering" method="get">
            <div class="woocommerce-ordering-div">
            	<select name="orderby" class="orderby">
            		<?php
            			$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
            				'menu_order' => __( 'Default sorting', 'woocommerce' ),
            				'popularity' => __( 'Sort by popularity', 'woocommerce' ),
            				'rating'     => __( 'Sort by average rating', 'woocommerce' ),
            				'date'       => __( 'Sort by newness', 'woocommerce' ),
            				'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
            				'price-desc' => __( 'Sort by price: high to low', 'woocommerce' )
            			) );

            			if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
            				unset( $catalog_orderby['rating'] );

            			foreach ( $catalog_orderby as $id => $name )
            				echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
            		?>
            	</select>
        	</div>
        	<?php
        		// Keep query string vars intact
        		foreach ( $_GET as $key => $val ) {
        			if ( 'orderby' == $key )
        				continue;
			
        			if (is_array($val)) {
        				foreach($val as $innerVal) {
        					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
        				}
			
        			} else {
        				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
        			}
        		}
        	?>
        </form>

<?php
	}
}


?>