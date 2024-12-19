<?php 
global $product;
$product_id = $product->get_id();
$image_size = isset($image_size) ? $image_size : 'woocommerce_thumbnail';

$already_sold = !empty($already_sold) ? $already_sold : 0;
$available = !empty($available) ? $available : 0;
$total_stock = $already_sold + $available;
$percentage = ( $available > 0 ? round($already_sold/$total_stock * 100) : 0 );

wp_enqueue_script( 'countdown' );
?>
<div class="product-block grid-deal style2" data-product-id="<?php echo esc_attr($product_id); ?>">

    <div class="grid-inner">
        <div class="block-inner">
            <figure class="image">
                <?php
                    rashy_product_image($image_size);
                    do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>
            </figure>
        </div>
        <div class="metas clearfix">
            <div class="clearfix">
                
                <?php rashy_woo_display_product_cat($product_id); ?>

                <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php
                    /**
                    * woocommerce_after_shop_loop_item_title hook
                    *
                    * @hooked woocommerce_template_loop_rating - 5
                    * @hooked woocommerce_template_loop_price - 10
                    */
                    do_action( 'woocommerce_after_shop_loop_item_title');
                ?>    
            </div>
            <div class="meta-bottom">
                <?php
                $end_date = !empty($end_date) ? strtotime($end_date) : '';
                if ( $end_date ) {
                    $format = '<div class="times"><div class="day">%%D%% '.esc_html__('Days', 'rashy').'</div><div class="hours">%%H%%</div><div class="minutes">%%M%%</div><div class="seconds">%%S%%</div></div>';
                    ?>
                    <div class="goal-countdown clearfix" data-time="timmer"
                        data-date="<?php echo date('m', $end_date).'-'.date('d', $end_date).'-'.date('Y', $end_date).'-'. date('H', $end_date) . '-' . date('i', $end_date) . '-' .  date('s', $end_date) ; ?>" data-format="<?php echo esc_attr($format); ?>">
                    </div>
                <?php } ?>

                <?php if ( $available && $already_sold ) { ?>
                <div class="special-progress">
                    <div class="progress">
                        <span class="progress-bar" style="<?php echo esc_attr('width:' . $percentage . '%'); ?>"></span>
                    </div>
                    <div class="info_sold clearfix">
                        <div class="pull-left">
                            <?php echo esc_html__('Available: ','rashy').'<span>'.$available.'</span>'; ?>
                        </div>
                        <div class="pull-right">
                            <?php echo esc_html__('Already Sold: ','rashy').'<span>'.$already_sold.'</span>'; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="groups-button flex-middle">
                <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

                <?php if (rashy_get_config('show_quickview', false)) { ?>
                    <div class="view">
                        <a href="javascript:void(0);" class="quickview" data-product_id="<?php echo esc_attr($product_id); ?>">
                            <?php echo esc_html('QUICK VIEW','rashy') ?>
                        </a>
                    </div>
                <?php } ?>
                 <?php
                    if ( class_exists( 'YITH_WCWL' ) ) {
                        echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                    }
                ?>
            </div>
        </div>
    </div>
</div>