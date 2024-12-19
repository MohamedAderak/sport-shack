<?php
get_header();
$sidebar_configs = rashy_get_woocommerce_layout_configs();
$check_elementor = '';

$display_mode = rashy_woocommerce_get_display_mode();
$layout_type = $display_mode;
if ( $display_mode == 'metro' ) {
	wp_enqueue_script( 'isotope-pkgd', get_template_directory_uri().'/js/isotope.pkgd.min.js', array( 'jquery', 'imagesloaded' ) );
}
?>
<?php 
	if(is_product_taxonomy() ){
		global $wp_query;
		$term =	$wp_query->queried_object;
		$e_template_id = get_term_meta( $term->term_id, 'e_template_id', true );
	}
	if ( empty($e_template_id) ) {
		$e_template_id = rashy_get_config('shop_elementor_template');
	}
	if( !empty($e_template_id) ) {
		$check_elementor = 'has-elementor';
	}
?>
<?php if ( rashy_get_config('show_shop_top_categories') ) { ?>
    <?php
        wc_get_template_part('content-product_top_cat');
    ?>
<?php } else { ?>
	<div class="shop-normal">
		<?php do_action( 'rashy_woo_template_main_before' ); ?>
	</div>
<?php } ?>

<section id="main-container" class="page-shop <?php echo esc_attr($check_elementor); ?> <?php echo apply_filters('rashy_woocommerce_content_class', 'container');?>">

	<?php
		if ( is_product_taxonomy() ) {
			?>
			<div class="category-description">
				<?php
				if ( $e_template_id ) {
					echo Rashy_Elementor_Extensions::render_page_content($e_template_id);
				} elseif ( function_exists('woocommerce_taxonomy_archive_description')) {
					woocommerce_taxonomy_archive_description();
				}
				?>
			</div>
			<?php
		} elseif ( $e_template_id ) {
			?>
			<div class="category-description">
				<?php echo Rashy_Elementor_Extensions::render_page_content($e_template_id); ?>
			</div>
			<?php
		}
	?>

	<?php rashy_before_content( $sidebar_configs ); ?>

	<div class="row">
		<?php rashy_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="archive-shop  col-xs-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">

			<div id="primary" class="content-area">
				<div id="content" class="site-content" role="main">

					<!-- product content -->
					
					
					<div id="goal-shop-products-wrapper" class="goal-shop-products-wrapper <?php echo esc_attr($layout_type); ?>" data-layout_type="<?php echo esc_attr($layout_type); ?>">
						
						<?php if ( have_posts() ) : ?>

							<?php do_action( 'woocommerce_before_shop_loop' ); ?>

							<?php woocommerce_product_loop_start(); ?>
							
							<?php woocommerce_product_subcategories( array( 'before' => '<div class="row subcategories-wrapper">', 'after' => '</div>' ) ); ?>
							
							<?php
								$attr = 'class="goal-products-wrapper products-wrapper-'.esc_attr($display_mode).'"';
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

							<?php woocommerce_product_loop_end(); ?>

							<?php do_action( 'woocommerce_after_shop_loop' ); ?>

						<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
							<?php do_action( 'woocommerce_no_products_found' ); ?>
						<?php endif; ?>

					</div>
				</div><!-- #content -->
			</div><!-- #primary -->
		</div><!-- #main-content -->
		
		<?php rashy_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php

get_footer();
