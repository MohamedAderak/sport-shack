<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$show_product_upsells = rashy_get_config('show_product_upsells', true);
if ( !$show_product_upsells ) {
    return;
}
$columns = rashy_get_config('upsells_product_columns', true);

global $product;

$upsells = $product->get_upsell_ids();

if ( sizeof( $upsells ) == 0 ) {
	return;
}

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => -1,
	'post__in'            => $upsells,
);

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<div class="related products  widget text-center ">
		<div class="woocommerce ">
			<div class="widget-title">
				<h3 ><?php esc_html_e( 'Up-Sells Products', 'rashy' ); ?></h3>
			</div>	
			
						<?php wc_get_template( 'layout-products/carousel.php' , array( 'loop' => $products, 'columns' => $columns, 'show_nav' => 1, 'slick_top' => 'slick-carousel-top' ) ); ?>
					
		</div>
	</div>
<?php endif;

wp_reset_postdata();