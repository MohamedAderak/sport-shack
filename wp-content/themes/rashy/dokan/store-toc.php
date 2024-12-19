<?php
/**
 * The Template for displaying all reviews.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

$vendor       = dokan()->vendor->get( get_query_var( 'author' ) );
$vendor_info  = $vendor->get_shop_info();
$map_location = $vendor->get_location();
$store_user   = get_userdata( get_query_var( 'author' ) );
$store_info   = dokan_get_store_info( $store_user->ID );
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

?>
<section id="main-container" class="page-shop margin-top-40 <?php echo apply_filters('rashy_woocommerce_content_class', 'container');?>">

    <?php do_action( 'woocommerce_before_main_content' ); ?>

    <div class="goal-dokan-store-wrap row">

        <?php if ( 'left' === $layout ) { ?>
            <div class="<?php echo esc_attr($class_sidebar); ?>">
                <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
            </div>
        <?php } ?>

        <div id="primary" class="content-area dokan-single-store <?php echo esc_attr($class_main); ?>">
            <div id="dokan-content" class="site-content store-review-wrap woocommerce" role="main">

                <?php dokan_get_template_part( 'store-header' ); ?>

                <div id="store-toc-wrapper">
                    <div id="store-toc">
                        <?php
                        if( ! empty( $vendor->get_store_tnc() ) ):
                        ?>
                            <h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'rashy' ); ?></h2>
                            <div>
                                <?php
                                    echo wp_kses_post( nl2br( $vendor->get_store_tnc() ) );
                                ?>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div><!-- #store-toc -->
                </div><!-- #store-toc-wrap -->

            </div><!-- #content .site-content -->
        </div><!-- #primary .content-area -->

        <div class="dokan-clearfix"></div>

        <?php if ( 'right' === $layout ) { ?>
            <div class="<?php echo esc_attr($class_sidebar); ?>">
            <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
        </div>
        <?php } ?>

    </div><!-- .dokan-store-wrap -->


    <?php do_action( 'woocommerce_after_main_content' ); ?>
</section>
<?php get_footer(); ?>
