<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Rashy
 * @since Rashy 1.0
 */

get_header();
$sidebar_configs = rashy_get_blog_layout_configs();

$columns = rashy_get_config('blog_columns', 1);
$bscol = floor( 12 / $columns );
$_count  = 0;

rashy_render_breadcrumbs();
?>
<section id="main-container" class="main-content archive-blog  <?php echo apply_filters('rashy_blog_content_class', 'container');?> inner">
		
	<?php rashy_before_content( $sidebar_configs ); ?>
	<div class="row">
		<?php rashy_display_sidebar_left( $sidebar_configs ); ?>
		<div id="main-content" class="col-sm-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header hidden">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					?>
						<?php get_template_part( 'content', 'search' ); ?>
					<?php
					$_count++;
				// End the loop.
				endwhile;

				// Previous/next page navigation.
				rashy_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );

			endif;
			?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<?php rashy_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php get_footer(); ?>