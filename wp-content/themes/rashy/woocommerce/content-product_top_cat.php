<?php
/**
 *  The template for displaying the shop header
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$style_class = rashy_get_config('shop_top_categories_style', 'style1');
$style = '';
if ( in_array($style_class, array('style1', 'style2', 'style4', 'style5')) ) {
    $image = rashy_get_config('shop_top_categories_breadcrumb_image');
    
    if ( !empty($image['id']) ) {
        $img = wp_get_attachment_image_src($image['id'], 'full');
        if ( !empty($img[0]) ) {
            $style = 'style="background-image:url(\''.esc_url($img[0]).'\');"';
        }
    }
}
$display_type = '';
if ( $style_class == 'style4' ) {
    $display_type = 'image';
} elseif ( $style_class == 'style5' ) {
    $display_type = 'icon';
}
?>
<div class="shop-top-categories <?php echo esc_attr($style_class); ?>" <?php echo trim($style); ?>>
	<div class="top-categories-inner">
		<div class="inner">
			<h1 class="shop-page-title"><?php woocommerce_page_title(); ?></h1>
			<ul class="list-category-products">
		    	<?php rashy_category_menu($display_type); ?>
		    </ul>
	    </div>
    </div>
</div>