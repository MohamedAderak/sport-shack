<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_taxonomy_description = is_product_taxonomy() ? true : false;

$display_mode = rashy_woocommerce_get_display_mode();
?> 

<?php
	// Page title
	printf( '<div id="goal-wp-title">%s</div>', wp_title( '&ndash;', false, 'right' ) );
?>

<?php if ( rashy_get_config('show_shop_top_categories') ) { ?>
    <?php
        wc_get_template_part('content-product_top_cat');
    ?>
<?php } ?>

<?php

$sidebar_configs = rashy_get_woocommerce_layout_configs();
rashy_display_sidebar_left( $sidebar_configs );
rashy_display_sidebar_right( $sidebar_configs );

?>


<div id="goal-shop-products-wrapper" class="goal-shop-products-wrapper">
<?php
	
	if ( have_posts() ) {

		global $woocommerce_loop, $wp_query;
		?>
			<?php do_action( 'woocommerce_before_shop_loop' ); ?>

			<?php woocommerce_product_loop_start(); ?>

            <?php woocommerce_product_subcategories( array( 'before' => '<div class="row subcategories-wrapper">', 'after' => '</div>' ) ); ?>
            
            <?php
				$attr = 'class="products-wrapper-'.esc_attr($display_mode).'"';
				if ( $display_mode == 'metro' ) {
					$attr = 'class="products-wrapper-mansory isotope-items row" data-isotope-duration="400" data-columnwidth=".col-sm-3"';
				}
			?>
			<div <?php echo trim($attr); ?>>
				<?php if ( $display_mode == 'grid' ) { ?>
					<div class="row-products-wrapper">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php wc_get_template_part( 'content', 'product' ); ?>
						<?php endwhile; // end of the loop. ?>
					</div>
				<?php } else { ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; // end of the loop. ?>
				<?php } ?>
			</div>
            <?php
		woocommerce_product_loop_end();
		
		do_action( 'woocommerce_after_shop_loop' );
		do_action( 'woocommerce_after_main_content' );
		
	} elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) {

		wc_get_template( 'loop/no-products-found.php' );

	}
?>
</div>

