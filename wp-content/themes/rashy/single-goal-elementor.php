<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Rashy
 * @since Rashy 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="//gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="wrapper-container" class="wrapper-container">

	<div id="goal-main-content">
		<section id="main-container" class="main-content container-fluid inner">
			
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					the_content();
				// End the loop.
				endwhile;
			?>
		</section>
	</div><!-- .site -->
</div>
<?php wp_footer(); ?>
</body>
</html>