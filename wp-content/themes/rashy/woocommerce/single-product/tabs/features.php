<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$features = get_post_meta( $post->ID, 'goal_product_features', true );
?>
<div class="features">
	<?php echo trim($features); ?>
</div>