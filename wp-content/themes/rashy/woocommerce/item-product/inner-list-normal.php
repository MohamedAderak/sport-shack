<?php 
global $product;
$product_id = $product->get_id();
$image_size = isset($image_size) ? $image_size : 'woocommerce_thumbnail';

?>
<div class="product-block list" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="shop-list-normal  flex-middle">
        <div class="image-left flex">
            <div class="block-inner flex-middle justify-content-center">
                <figure class="image">
                    <?php
                        rashy_product_image($image_size);
                    ?>
                </figure>
            </div>
        </div>
        <div class="info-right flex">
            <div class="metas clearfix">
            	<h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
               <div class="rating clearfix">
		            <?php
		                $rating_html = wc_get_rating_html( $product->get_average_rating() );
		                $count = $product->get_rating_count();
		                if ( $rating_html ) {
		                    echo trim( $rating_html );
		                } else {
		                    echo '<div class="star-rating"></div>';
		                }
		                // echo '<span class="counts">('.$count.')</span>';
		            ?>
		        </div>

                
                <?php
                    /**
                    * woocommerce_after_shop_loop_item_title hook
                    *
                    * @hooked woocommerce_template_loop_rating - 5
                    * @hooked woocommerce_template_loop_price - 10
                    */
                    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
                    do_action( 'woocommerce_after_shop_loop_item_title');
                ?>    
                
            </div>
        </div>
        
    </div>
</div>