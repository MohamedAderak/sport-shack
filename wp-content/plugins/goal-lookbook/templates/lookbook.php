<?php

if (!isset($slug) || !$slug)
	return;

$args = array(
  'name'        => $slug,
  'post_type'   => 'lookbook',
  'post_status' => 'publish',
  'numberposts' => 1
);
$posts = get_posts($args);
if( $posts ) {
  $post = $posts[0];
} else {
	return;
}
$zones = get_post_meta($post->ID, 'goal_lookbook_zones', true);
$image = get_post_meta($post->ID, 'goal_lookbook_image', true);

if (empty($image))
	return;
$style = !empty($style) ? $style : '';
?>
<div class="goal-lookbook <?php echo esc_attr($style); ?>" style="position: relative">
	<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
	<?php foreach ($zones as $zone):
		$post = GoalLookbook_Helper::getPost($zone);
		if ($post):
	?>
			<div data-href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="mapper-pin"
				style="width:<?php echo esc_attr($zone['width']); ?>px;height:<?php echo esc_attr($zone['height']); ?>px;left:<?php echo esc_attr($zone['x1']); ?>% ;top:<?php echo esc_attr($zone['y1']); ?>%; position: absolute;">
				<div class="mapper-pin-wrapper">
					<a href="<?php echo get_permalink( $post->ID ); ?>">
					</a>
					<div class="mapper-popup">
						<?php
							if ( has_post_thumbnail( $post->ID ) ) {
						?>
							<div class="image">
								<a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>">
						        	<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?>
						        </a>
							</div>
						<?php } ?>
						<div class="content">
							<h4>
								<a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>">
									<?php echo trim($post->post_title); ?>
								</a>
							</h4>
							<?php if ($post->post_type == 'product'){
								$product = wc_get_product( $post->ID );
							?>
								<div class="price"><?php echo ($product->get_price_html()); ?></div>
								<div class="rating clearfix">
								<?php
					            	if($rating_html = wc_get_rating_html( $product->get_average_rating() )){
					            		echo trim( wc_get_rating_html( $product->get_average_rating() ) );
					            	}
					        	?>
							    </div>
							<?php } elseif ($post->post_excerpt) { ?>
								<div class="content"><?php echo $post->post_excerpt; ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>