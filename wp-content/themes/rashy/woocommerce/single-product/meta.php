<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_product_meta = rashy_get_config('show_product_meta', true);
if ( !$show_product_meta ) {
	return;
}
global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><span class="sub_title"><?php esc_html_e( 'SKU:', 'rashy' ); ?> </span> <span class="sku"><?php echo trim(( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'rashy' )); ?></span></span>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in"><span class="sub_title">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'rashy' ) . '</span> ', '</span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( '<span class="sub_title">Tag:</span>', '<span class="sub_title">Tags:</span>', count( $product->get_tag_ids() ), 'rashy' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>