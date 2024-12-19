<?php
if ( empty($loop) || !is_object( $loop) ) {
    return;
}
$columns = !empty($columns) ? $columns : 3;
$columns_tablet = !empty($columns_tablet) ? $columns_tablet : 3;
$columns_mobile = !empty($columns_mobile) ? $columns_mobile : 2;

$mdcol = 12/$columns;
$smcol = 12/$columns_tablet;
$xscol = 12/$columns_mobile;

if( $columns == 5 ){
	$mdcol = '5c';
}
if( $columns_tablet == 5 ){
    $smcol = '5c';
}

if( $columns_mobile == 5 ){
    $xscol = '5c';
}

$product_item = isset($product_item) ? $product_item : 'inner';

wc_set_loop_prop( 'loop', 0 );
wc_set_loop_prop( 'columns', $columns );

$classes = array();

$classes[] = 'col-md-'.$mdcol.' col-sm-'.$smcol.' col-xs-'.$xscol;

?>
<div class="products products-grid <?php if (rashy_get_config('colection_gutter', false)) { ?> colection_gutter <?php } ?>">
	<div class="products-wrapper-grid">
		<div class="row row-products row-products-wrapper">
			
			<?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); global $product;
				$pclasses = $classes;
				if ( $count%$columns == 0 ) {
					$pclasses[] = 'md-clearfix lg-clearfix ';
				}
				if( $count%$columns_tablet == 0 ){
					$pclasses[] = 'sm-clearfix ';
				}
				if( $count%$columns_mobile == 0 ){
					$pclasses[] = 'xs-clearfix ';
				}
			?>
				<div <?php wc_product_class( $pclasses, $product ); ?>>
				 	<?php wc_get_template_part( 'item-product/'.$product_item ); ?>
				</div>
			<?php $count++; endwhile; ?>

		</div>
	</div>
</div>
<?php wp_reset_postdata(); ?>