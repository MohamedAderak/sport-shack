<?php
/**
 * Plugin Name: Goal Salespopup
 * Plugin URI: http://goalthemes.com/plugins/goal-salespopup/
 * Description: Create Salespopups.
 * Version: 1.0.0
 * Author: GoalThemes
 * Author URI: #
 * Requires at least: 3.8
 * Tested up to: 4.6
 *
 * Text Domain: goal-salespopup
 * Domain Path: /languages/
 *
 * @package goal-salespopup
 * @category Plugins
 * @author GoalThemes
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists("GoalSalespopup") ){
	
	final class GoalSalespopup{

		/**
		 * @var GoalSalespopup The one true GoalSalespopup
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * GoalSalespopup Settings Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $goalsalespopup_settings;

		/**
		 *
		 */
		public function __construct() {

		}

		/**
		 * Main GoalSalespopup Instance
		 *
		 * Insures that only one instance of GoalSalespopup exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since     1.0.0
		 * @static
		 * @staticvar array $instance
		 * @uses      GoalSalespopup::setup_constants() Setup the constants needed
		 * @uses      GoalSalespopup::includes() Include the required files
		 * @uses      GoalSalespopup::load_textdomain() load the language files
		 * @see       GoalSalespopup()
		 * @return    GoalSalespopup
		 */
		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof GoalSalespopup ) ) {
				self::$instance = new GoalSalespopup;
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->libraries();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 *
		 */
		public function setup_constants(){
			// Plugin version
			if ( ! defined( 'GOALSALESPOPUP_VERSION' ) ) {
				define( 'GOALSALESPOPUP_VERSION', '1.0.0' );
			}

			// Plugin Folder Path
			if ( ! defined( 'GOALSALESPOPUP_PLUGIN_DIR' ) ) {
				define( 'GOALSALESPOPUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'GOALSALESPOPUP_PLUGIN_URL' ) ) {
				define( 'GOALSALESPOPUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'GOALSALESPOPUP_PLUGIN_FILE' ) ) {
				define( 'GOALSALESPOPUP_PLUGIN_FILE', __FILE__ );
			}
		}

		public function includes() {
			global $goal_salespopup_options;

			require_once GOALSALESPOPUP_PLUGIN_DIR . 'inc/class-settings.php';

			$goal_salespopup_options = goal_salespopup_get_settings();
			
			require_once GOALSALESPOPUP_PLUGIN_DIR . 'inc/class-helper.php';
			require_once GOALSALESPOPUP_PLUGIN_DIR . 'inc/class-scripts.php';
			
		}

		public static function libraries() {
			require_once GOALSALESPOPUP_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_ajax_search/cmb2-field-ajax-search.php';
			require_once GOALSALESPOPUP_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_min_max/cmb2-field-min-max.php';
		}
		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for GoalSalespopup's languages directory
			$lang_dir = dirname( plugin_basename( GOALSALESPOPUP_PLUGIN_FILE ) ) . '/languages/';
			$lang_dir = apply_filters( 'goalsalespopup_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'goal-salespopup' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'goal-salespopup', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/goal-salespopup/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/goalsalespopup folder
				load_textdomain( 'goal-salespopup', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/goalsalespopup/languages/ folder
				load_textdomain( 'goal-salespopup', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'goal-salespopup', false, $lang_dir );
			}
		}

	}
}

function goal_salespopup() {
	return GoalSalespopup::getInstance();
}

goal_salespopup();
