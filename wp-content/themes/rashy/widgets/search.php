<?php 

extract( $args );
extract( $instance );
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  .trim( $title ) . $after_title;
}

?>
<div class="widget-search widget-content">

    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
		
	  	<div class="input-group">
	  		<input type="text" placeholder="<?php esc_attr_e( 'Search', 'rashy' ); ?>" name="s" class="goal-search form-control"/>
			<span class="input-group-btn">
				<button type="submit" class="btn btn-sm btn-search"><i class="fa-search fa"></i></button>
			</span>
	  	</div>
		<?php if ( isset($post_type) && $post_type ): ?>
			<input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>" class="post_type" />
		<?php endif; ?>
	</form>

</div>