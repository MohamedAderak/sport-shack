<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

$layout = rashy_product_get_layout_type();

?>
<?php if ( $layout == 'v2' || $layout == 'v3' ) { ?>
	<div class="container-fluid no-padding">
<?php } else { ?>
	<div class="container-inner">
<?php }

	/**
	 * Hook: woocommerce_before_single_product.
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form(); // WPCS: XSS ok.
		return;
	}

	if ( method_exists('GoalSizeguides_Helper', 'display') ) {
		add_action( 'woocommerce_single_product_summary', array('GoalSizeguides_Helper', 'display'), 25 );
	}

	if(class_exists( 'YITH_WCWL' ) || (rashy_get_config('show_product_social_share', false)) ){
	    add_action( 'woocommerce_single_product_summary', 'rashy_woocomerce_wishlist_share_wrapper_open', 30 );
	    // add_filter( 'woocommerce_single_product_summary', 'rashy_woocommerce_share_box', 38 );
	    add_action( 'woocommerce_single_product_summary', 'rashy_woocomerce_wishlist_share_wrapper_close', 39 );
	}
?>

	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'details-product layout-'.$layout, $product ); ?>>

			<?php
				$style = '';
				if( $layout == 'v2' ){
					$bg_color = get_post_meta($post->ID, 'goal_product_bg_color', true);
					
					if ( $bg_color ) {
						$style = 'style="background-color:'.$product_bg_color.'"';
					} else {
						$bg_color = rashy_get_config('product_bg_color');
						if ( $bg_color  ) {
				            $style = 'style="background-color:'.$bg_color.'"';
				        }
			        }
		        }
		        // sticky
		        $main_class = $sticky_class = '';
		        if ( in_array($layout, array('v3', 'v4')) ) {
		        	if ( rashy_get_config('enable_sticky_cart', true) ) {
			        	wp_enqueue_script( 'sticky-kit' );
			        	$main_class = 'product-v-wrapper';
			        	$sticky_class = 'sticky-this';
			        }
		        }
			?>
			<div class="top-content" <?php echo trim($style);?>>
				
					<div class="row top-row <?php echo esc_attr($main_class); ?>">
						<div class="col-md-7 col-xs-12">
							<div class="image-mains clearfix">
								<?php
									/**
									 * woocommerce_before_single_product_summary hook
									 *
									 * @hooked woocommerce_show_product_sale_flash - 10
									 * @hooked woocommerce_show_product_images - 20
									 */
									do_action( 'woocommerce_before_single_product_summary' );
									if($layout == 'v4' ){
										rashy_woocommerce_delivery_info();
									}
								?>
							</div>
						</div>
						<div class="col-md-5 col-xs-12 right-info <?php echo esc_attr($sticky_class); ?>">
							<div class="information">
								<div class="summary entry-summary">
									<?php
										/**
										 * woocommerce_single_product_summary hook
										 *
										 * @hooked woocommerce_template_single_title - 5
										 * @hooked woocommerce_template_single_rating - 10
										 * @hooked woocommerce_template_single_price - 10
										 * @hooked woocommerce_template_single_excerpt - 20
										 * @hooked woocommerce_template_single_add_to_cart - 30
										 * @hooked woocommerce_template_single_meta - 40
										 * @hooked woocommerce_template_single_sharing - 50
										 */

										remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
										remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
										// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

										add_action( 'woocommerce_single_product_summary', 'rashy_product_breadcrumbs_navigation', 1 );
										add_action( 'woocommerce_single_product_summary', 'rashy_product_price_rating_open', 9 );
										add_action( 'woocommerce_single_product_summary', 'rashy_product_price_rating_close', 12 );
										add_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

										add_filter( 'woocommerce_single_product_summary', 'rashy_woocommerce_share_box', 100 );
										
										if ( rashy_get_config('show_product_review_tab', true) ) {
											add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );
										}

										if ( $layout == 'v3' ||  $layout == 'v4' || $layout == 'v6' || $layout == 'v7' ) {
											add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 200 );
										}
										if ( $layout == 'v1' || $layout == 'v2'  || $layout == 'v5' || $layout == 'v7' ) {
											add_action( 'woocommerce_single_product_summary', 'rashy_woocommerce_delivery_info', 150 );
										}

										do_action( 'woocommerce_single_product_summary' );
									?>
								</div><!-- .summary -->
							</div>
						</div>
					</div>
			</div>
		
			<?php
				/**
				 * woocommerce_after_single_product_summary hook
				 *
				 * @hooked woocommerce_output_product_data_tabs - 10
				 * @hooked woocommerce_upsell_display - 15
				 * @hooked woocommerce_output_related_products - 20
				 */
				if ( $layout == 'v3' || $layout == 'v4' || $layout == 'v6' || $layout == 'v7' ) {
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				}
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
				do_action( 'woocommerce_after_single_product_summary' );
			?>

			<meta itemprop="url" content="<?php the_permalink(); ?>" />
		
	</div><!-- #product-<?php the_ID(); ?> -->
	<?php if ( rashy_get_config('show_product_sticky_add_to_cart') && $product->get_type() !== 'grouped' ) { ?>
		<div class="add-to-cart-bottom-wrapper hidden-xs hidden-sm">
			<div class="container">
				<div class="flex-middle row">
					<div class="inner-left col-xs-3">
						<div class="flex-middle">
					        <div class="product-image">
					            <?php echo trim($product->get_image()); ?>
					        </div>
					        <h3 class="name"><?php the_title(); ?></h3>
				        </div>
			        </div>
			        <div class="inner-right col-xs-9">
			        	<div class="pull-right">
			        		<div class="flex-middle">
				        		<?php echo trim($product->get_price_html()); ?>
				        		<?php woocommerce_template_single_add_to_cart(); ?>
			        		</div>
			        	</div>
			        </div>
		        </div>
	        </div>
	    </div>
    <?php } ?>
	<?php do_action( 'woocommerce_after_single_product' ); ?>
</div>