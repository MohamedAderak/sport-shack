<?php
global $product;
$time_sale = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );

?>
<?php if ( $time_sale ) { ?>
	<div class="special-product">
		<div class="time">
		    <span><?php echo esc_html__( 'Start for you in: ', 'rashy' ); ?></span>
		    <div class="goal-countdown clearfix" data-time="timmer"
		        data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
		    </div>
		</div>
	</div>
<?php } ?>