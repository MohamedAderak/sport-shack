<?php
if ( empty($loop) || !is_object( $loop) ) {
    return;
}
$product_item = isset($product_item) ? $product_item : 'inner';

$show_nav = isset($show_nav) ? $show_nav : false;
$show_pagination = isset($show_pagination) ? $show_pagination : false;

$rows = isset($rows) ? $rows : 1;
$columns = !empty($columns) ? $columns : 3;
$columns_tablet = !empty($columns_tablet) ? $columns_tablet : 3;
$columns_mobile = !empty($columns_mobile) ? $columns_mobile : 2;

$slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
$slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
$slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;

$arrow_position = (!empty($arrow_position)) ? $arrow_position : '';
$slick_top = (!empty($slick_top)) ? $slick_top : '';

$elementor_element = isset($elementor_element) ? $elementor_element : false;
$infinite_loop = isset($infinite_loop) ? $infinite_loop : false;
$autoplay = isset($autoplay) ? $autoplay : false;
?>
<div class="products-grid products-slide ">
        <div class="products-wrapper-grid">
            <div class="row-products-wrapper">
                <div class="slick-carousel products  <?php echo esc_attr($arrow_position.' '.$navigation_position); ?>" <?php echo trim(!$elementor_element ? 'data-carousel="slick"' : ''); ?> 
                    data-items="<?php echo esc_attr($columns); ?>" 
                    data-smallmedium="<?php echo esc_attr( $columns_tablet ); ?>" 
                    data-extrasmall="<?php echo esc_attr($columns_mobile); ?>" 
                    data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>" 
                    data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>" 
                    data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>" 
                    data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" 
                    data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" 
                    data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" 
                    data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>" 
                    data-rows="<?php echo esc_attr( $rows ); ?>">

                    <?php wc_set_loop_prop( 'loop', 0 ); ?>
                    <?php $i = 0; while ( $loop->have_posts() ): $loop->the_post(); global $product; ?>
                        <div class="item">
                            <div class="product clearfix">
                                <?php wc_get_template_part( 'item-product/'.$product_item ); ?>
                            </div>
                        </div>
                    <?php $i++; endwhile; ?>
                    
                </div>
            </div>
        </div>
    </div>

<?php wp_reset_postdata(); ?>