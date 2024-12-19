<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Rashy
 * @since Rashy 1.0
 */
/*
*Template Name: 404 Page
*/
get_header();
rashy_render_breadcrumbs();
$icon = rashy_get_config('icon-img');
?>
<section class="page-404">
	<div id="main-container" class="inner">
		<div id="main-content" class="main-page">
			<section class="error-404 not-found clearfix">
				<div class="container">
					<div class="row">
						<div class="col-sm-12 hidden-xs">
							<?php if( !empty($icon) && !empty($icon['url'])) { ?>
								<img src="<?php echo esc_url( $icon['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php }else{ ?>
								<img src="<?php echo esc_url_raw( get_template_directory_uri().'/images/error.png'); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</div>
						<div class="col-sm-12">
							<div class="slogan">
								<h4 class="title-big">
									<?php
										$title = rashy_get_config('404_title');
										if ( !empty($title) ) {
											echo esc_html($title);
										} else {
											esc_html_e('Oops! This page Could Not Be Found!', 'rashy');
										}
									?>
								</h4>
							</div>
							<div class="page-content">
								<div class="description">
									<?php
									$desc = rashy_get_config('404_description');
									if ( !empty($desc) ) {
										echo trim($desc);
									} else {
										esc_html_e('Sorry bit the page you are looking for does not exist, have been removed or name changed', 'rashy');
									}
									?>
								</div>
								<div class="return">
									<a class="btn btn-theme" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__('Go to homepage','rashy') ?><i class="icon-arrow-right" aria-hidden="true"></i></a>
								</div>
							</div><!-- .page-content -->
						</div>
						
					</div>
				</div>
			</section><!-- .error-404 -->
		</div><!-- .content-area -->
	</div>
</section>
<?php get_footer(); ?>