<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$show_product_releated = rashy_get_config('show_product_releated', true);
if ( !$show_product_releated  ) {
    return;
}

global $product;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}
$columns = rashy_get_config('releated_product_columns', 6);
$per_page = rashy_get_config('number_product_releated', 6);
$related = wc_get_related_products( $product->get_id(), $per_page );

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $per_page,
	'orderby'              => $orderby,
	'post__in'             => $related,
	'post__not_in'         => array( $product->get_id() )
) );

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>
	<div class="related products  widget text-center ">
		<div class="woocommerce ">
			<div class="widget-title">
				<h3 ><?php esc_html_e( 'Related Products', 'rashy' ); ?></h3>
			</div>	
			
						<?php wc_get_template( 'layout-products/carousel.php' , array( 'loop' => $products, 'columns' => $columns, 'show_nav' => 1, 'slick_top' => 'slick-carousel-top' ) ); ?>
					
		</div>
	</div>
<?php endif;
wp_reset_postdata();