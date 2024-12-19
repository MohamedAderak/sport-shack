<?php
/**
 * Goal Framework Plugin
 *
 * A simple, truly extensible and fully responsive options framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Goal Framework
 * Plugin URI:      #
 * Description:     Goal framework for wordpress theme
 * Author:          Team GoalTheme
 * Author URI:      #
 * Version:         2.1
 * Text Domain:     goal-framework
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     languages
 */

define( 'GOAL_FRAMEWORK_VERSION', '2.1');
define( 'GOAL_FRAMEWORK_URL', plugin_dir_url( __FILE__ ) );
define( 'GOAL_FRAMEWORK_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Redux Framework
 *
 */
if ( !class_exists( 'ReduxFramework' ) && file_exists( GOAL_FRAMEWORK_DIR . 'libs/redux/redux-core/framework.php' ) ) {
    require_once( GOAL_FRAMEWORK_DIR . 'libs/redux/redux-core/framework.php' );
    require_once( GOAL_FRAMEWORK_DIR . 'libs/loader.php' );
    define( 'GOAL_FRAMEWORK_REDUX_ACTIVED', true );
} else {
	define( 'GOAL_FRAMEWORK_REDUX_ACTIVED', true );
}
/**
 * Custom Post type
 *
 */
add_action( 'init', 'goal_framework_register_post_types', 1 );
/**
 * Import data sample
 *
 */
require GOAL_FRAMEWORK_DIR . 'importer/import.php';
/**
 * functions
 *
 */
require GOAL_FRAMEWORK_DIR . 'functions.php';
require GOAL_FRAMEWORK_DIR . 'functions-preset.php';
/**
 * Widgets Core
 *
 */
require GOAL_FRAMEWORK_DIR . 'classes/class-goal-widgets.php';
add_action( 'widgets_init',  'goal_framework_widget_init' );

require GOAL_FRAMEWORK_DIR . 'classes/createplaceholder.php';
/**
 * Init
 *
 */
function goal_framework_init() {
	$demo_mode = apply_filters( 'goal_framework_register_demo_mode', false );
	if ( $demo_mode ) {
		goal_framework_init_redux();
	}
	$enable_tax_fields = apply_filters( 'goal_framework_enable_tax_fields', false );
	if ( $enable_tax_fields ) {
		if ( !class_exists( 'Taxonomy_MetaData_CMB2' ) ) {
			require_once GOAL_FRAMEWORK_DIR . 'libs/cmb2/taxonomy/Taxonomy_MetaData_CMB2.php';
		}
	}
}
add_action( 'init', 'goal_framework_init', 100 );

function goal_framework_load_textdomain() {

	$lang_dir = GOAL_FRAMEWORK_DIR . 'languages/';
	$lang_dir = apply_filters( 'goal-framework_languages_directory', $lang_dir );

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale', get_locale(), 'goal-framework' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'goal-framework', $locale );

	// Setup paths to current locale file
	$mofile_local  = $lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/goal-framework/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/goal-framework folder
		load_textdomain( 'goal-framework', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/goal-framework/languages/ folder
		load_textdomain( 'goal-framework', $mofile_local );
	} else {
		// Load the default language files
		load_plugin_textdomain( 'goal-framework', false, $lang_dir );
	}
}

add_action( 'plugins_loaded', 'goal_framework_load_textdomain' );