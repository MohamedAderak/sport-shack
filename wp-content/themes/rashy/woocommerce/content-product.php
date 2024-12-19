<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$woo_display = rashy_woocommerce_get_display_mode();
if ( $woo_display == 'list' ) { 	
	$classes[] = 'list-products col-xs-12';
?>
	<div <?php wc_product_class( $classes, $product ); ?>>
	 	<?php wc_get_template_part( 'item-product/inner', 'list' ); ?>
	</div>
<?php
} else {
	// Store loop count we're currently on
	if ( empty( $woocommerce_loop['loop'] ) ) {
		$woocommerce_loop['loop'] = 0;
	}
	// Store column count for displaying the grid
	if(!empty($columns)){
		$woocommerce_loop['columns'] = $columns;
	}else{
		$woocommerce_loop['columns'] = rashy_woocommerce_shop_columns(4);
	}
	// Ensure visibility
	if ( ! $product || ! $product->is_visible() ) {
		return;
	}

	$bcol = 12/$woocommerce_loop['columns'];
	if($woocommerce_loop['columns'] == 5){
		$bcol = '5c';
	}
	if($woocommerce_loop['columns'] >=4 ){
		$classes[] = 'col-md-'.$bcol.' col-sm-'.$bcol.' col-xs-6 ';
	}else{
		$classes[] = 'col-md-'.$bcol.($woocommerce_loop['columns'] > 1 ? ' col-xs-6 ' : '').($woocommerce_loop['columns'] > 2 ? ' col-sm-4 ' : ' col-sm-6');
	}

	$item_style = rashy_get_config('product_item_style', 'v1');
	if ( $item_style == 'v1' ) {
		$item_style = '';
	}
	?>
	<div <?php wc_product_class( $classes, $product ); ?>>
		<?php wc_get_template_part( 'item-product/inner', $item_style ); ?>
	</div>
<?php } ?>