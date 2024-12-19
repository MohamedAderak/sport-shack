<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$crosssells = WC()->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) return;

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => -1,
	'post__in'            => $crosssells
);

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<div class="cross-sells products widget">
		<div class="woocommerce">
			<h2 class="widget-title"><?php esc_html_e( 'You may be interested in&hellip;', 'rashy' ) ?></h2>

		<?php wc_get_template( 'layout-products/carousel.php',array( 'loop'=>$products,'columns'=> 4, 'show_nav' => 1, 'slick_top' => 'slick-carousel-top' ) ); ?>

	</div>

<?php endif;
