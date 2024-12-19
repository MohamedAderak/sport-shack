<?php
if ( empty($loop) || !is_object( $loop) ) {
    return;
}
$product_item = isset($product_item) ? $product_item : 'inner-list-small';
?>
<ul class="goal-products-list">
	<?php wc_set_loop_prop( 'loop', 0 ); ?>
	<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
		<li class="product-block widget-product">
			<?php wc_get_template_part( 'item-product/'.$product_item ); ?>
		</li>
	<?php endwhile; ?>
</ul>
<?php wp_reset_postdata(); ?>