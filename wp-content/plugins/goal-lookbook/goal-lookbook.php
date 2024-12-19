<?php
/**
 * Plugin Name: Goal Lookbook
 * Plugin URI: http://goalthemes.com/plugins/goal-lookbook/
 * Description: Create Lookbooks.
 * Version: 1.0.0
 * Author: GoalThemes
 * Author URI: http://goalthemes.com
 * Requires at least: 3.8
 * Tested up to: 4.6
 *
 * Text Domain: goal-lookbook
 * Domain Path: /languages/
 *
 * @package goal-lookbook
 * @category Plugins
 * @author GoalThemes
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists("GoalLookbook") ){
	
	final class GoalLookbook{

		/**
		 * @var GoalLookbook The one true GoalLookbook
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * GoalLookbook Settings Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $goallookbook_settings;

		/**
		 *
		 */
		public function __construct() {

		}

		/**
		 * Main GoalLookbook Instance
		 *
		 * Insures that only one instance of GoalLookbook exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since     1.0.0
		 * @static
		 * @staticvar array $instance
		 * @uses      GoalLookbook::setup_constants() Setup the constants needed
		 * @uses      GoalLookbook::includes() Include the required files
		 * @uses      GoalLookbook::load_textdomain() load the language files
		 * @see       GoalLookbook()
		 * @return    GoalLookbook
		 */
		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof GoalLookbook ) ) {
				self::$instance = new GoalLookbook;
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 *
		 */
		public function setup_constants(){
			// Plugin version
			if ( ! defined( 'GOALLOOKBOOK_VERSION' ) ) {
				define( 'GOALLOOKBOOK_VERSION', '1.0.0' );
			}

			// Plugin Folder Path
			if ( ! defined( 'GOALLOOKBOOK_PLUGIN_DIR' ) ) {
				define( 'GOALLOOKBOOK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'GOALLOOKBOOK_PLUGIN_URL' ) ) {
				define( 'GOALLOOKBOOK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'GOALLOOKBOOK_PLUGIN_FILE' ) ) {
				define( 'GOALLOOKBOOK_PLUGIN_FILE', __FILE__ );
			}
		}

		public function includes() {
			require_once GOALLOOKBOOK_PLUGIN_DIR . 'inc/class-template-loader.php';
			require_once GOALLOOKBOOK_PLUGIN_DIR . 'inc/shortcode.php';
			require_once GOALLOOKBOOK_PLUGIN_DIR . 'inc/class-helper.php';
			
			GoalLookbook_Helper::includes( GOALLOOKBOOK_PLUGIN_DIR . 'inc/post-types/*.php' );

			add_action( 'wp_enqueue_scripts', array( $this, 'style' ) );
		}

		public function style() {
			wp_enqueue_style( 'goal-lookbook-style', GOALLOOKBOOK_PLUGIN_URL . 'assets/style.css' );
		}
		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for GoalLookbook's languages directory
			$lang_dir = dirname( plugin_basename( GOALLOOKBOOK_PLUGIN_FILE ) ) . '/languages/';
			$lang_dir = apply_filters( 'goallookbook_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'goal-lookbook' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'goal-lookbook', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/goal-lookbook/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/goallookbook folder
				load_textdomain( 'goal-lookbook', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/goallookbook/languages/ folder
				load_textdomain( 'goal-lookbook', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'goal-lookbook', false, $lang_dir );
			}
		}

	}
}

function goal_lookbook() {
	return GoalLookbook::getInstance();
}

goal_lookbook();
