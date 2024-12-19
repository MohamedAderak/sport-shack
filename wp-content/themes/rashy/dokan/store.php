<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );

$class_sidebar = 'col-sm-12 col-xs-12';
$class_main = 'col-sm-12 col-xs-12';
if ( 'left' === $layout ||  'right' === $layout ) {
    if( rashy_get_config('product_archive_fullwidth') ) {
        $class_sidebar .= ' col-lg-2 col-md-3';
        $class_main .= ' col-lg-10 col-md-9';
    }else{
        $class_sidebar .= ' col-lg-3 col-md-3';
        $class_main .= ' col-lg-9 col-md-9';
    }
}

$display_mode = rashy_woocommerce_get_display_mode();
$layout_type = $display_mode;
if ( $display_mode == 'metro' ) {
    wp_enqueue_script( 'isotope-pkgd', get_template_directory_uri().'/js/isotope.pkgd.min.js', array( 'jquery', 'imagesloaded' ) );
}

?>
    <section id="main-container" class="page-shop margin-top-40 <?php echo apply_filters('rashy_woocommerce_content_class', 'container');?>">
        <?php //do_action( 'woocommerce_before_main_content' ); ?>

        <div class="goal-dokan-store-wrap row">

            <?php if ( 'left' === $layout ) { ?>
                <div class="<?php echo esc_attr($class_sidebar); ?>">
                    <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
                </div>
            <?php } ?>

            <div id="dokan-primary" class="dokan-single-store <?php echo esc_attr($class_main); ?>">
                <div id="dokan-content" class="store-page-wrap woocommerce" role="main">

                    <?php dokan_get_template_part( 'store-header' ); ?>

                    <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

                    <div id="goal-shop-products-wrapper" class="goal-shop-products-wrapper <?php echo esc_attr($layout_type); ?>" data-layout_type="<?php echo esc_attr($layout_type); ?>">
                        <?php if ( have_posts() ) { ?>

                            <div class="seller-items">

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

                                <?php woocommerce_product_loop_end(); ?>

                            </div>

                            <?php dokan_content_nav( 'nav-below' ); ?>

                        <?php } else { ?>

                            <p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'rashy' ); ?></p>

                        <?php } ?>
                    </div>
                </div>

            </div><!-- .dokan-single-store -->

            <?php if ( 'right' === $layout ) { ?>
                <div class="<?php echo esc_attr($class_sidebar); ?>">
                    <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
                </div>
            <?php } ?>

        </div><!-- .dokan-store-wrap -->

        <?php //do_action( 'woocommerce_after_main_content' ); ?>
    </section>
<?php get_footer( 'shop' ); ?>
