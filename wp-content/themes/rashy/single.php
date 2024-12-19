<?php

get_header();

$sidebar_configs = rashy_get_blog_layout_configs();
if( isset($sidebar_configs['right']) ){
	$check = 'has-right';
} elseif ( isset($sidebar_configs['left']) ){
	$check = 'has-left';
} elseif (isset($sidebar_configs['right']) && isset( $sidebar_configs['left']) ) {
	$check = '';
} else{
	$check = 'no-sidebar';
}
rashy_render_breadcrumbs();
?>
<section id="main-container" class="main-content single-blog <?php echo apply_filters( 'rashy_blog_content_class', 'container' ); ?> inner">
	<?php rashy_before_content( $sidebar_configs ); ?>
	<div class="row">
		<?php rashy_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-xs-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<div id="primary" class="content-area <?php echo esc_attr($check); ?>">
				<div id="content" class="site-content detail-post" role="main">
					<?php
						// Start the Loop.
						while ( have_posts() ) : the_post();

							/*
							 * Include the post format-specific template for the content. If you want to
							 * use this in a child theme, then include a file called called content-___.php
							 * (where ___ is the post format) and that will be used instead.
							 */
							get_template_part( 'template-posts/single/inner' );
							
							get_template_part( 'template-parts/author-bio' );
							
			                // If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

							if ( rashy_get_config('show_blog_releated', false) ): ?>
								<?php get_template_part( 'template-parts/posts-releated' ); ?>
			                <?php

			                endif;
						// End the loop.
						endwhile;
					?>
				</div><!-- #content -->
			</div><!-- #primary -->
		</div>	
		
		<?php rashy_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>	
</section>
<?php get_footer(); ?>