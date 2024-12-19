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
	<link rel="profile" href="//gmpg.org/xfn/11" />

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( rashy_get_config('preload', true) ) {
	$preload_icon = rashy_get_config('media-preload-icon');
	$preload_icon_image_img = '';
	if ( (isset($preload_icon['url'])) && (trim($preload_icon['url']) != "" ) ) {
        if (is_ssl()) {
            $preload_icon_image_img = str_replace("http://", "https://", $preload_icon['url']);		
        } else {
            $preload_icon_image_img = $preload_icon['url'];
        }
    }
?>
	<div class="goal-page-loading">
        <div class="goal-loader-inner" style="<?php echo esc_attr($preload_icon_image_img ? 'background-image: url(\''.$preload_icon_image_img.'\')' : ''); ?>"></div>
    </div>
<?php } ?>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>
<div id="wrapper-container" class="wrapper-container">

	<?php get_template_part( 'headers/mobile/offcanvas-menu' ); ?>
	<?php get_template_part( 'headers/mobile/header-mobile' ); ?>

	<?php
		$header = apply_filters( 'rashy_get_header_layout', rashy_get_config('header_type') );
		get_template_part( 'headers/'.$header );
		if ( !empty($header) ) {
			rashy_display_header_builder($header);
		} else {
			get_template_part( 'headers/default' );
		}
	?>
	<div id="goal-main-content">