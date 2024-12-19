<?php
/**
 * WP Plugin Install Tab
 *
 * @package   quadlayers/wp-plugin-install-tab
 * @author    Francisco Mastromarino
 * @link      https://github.com/quadlayers/wp-plugin-install-tab
 * @copyright Copyright (c) 2023
 * @license   GPL-3.0
 */

namespace QuadLayers\WP_Plugin_Install_Tab;

use QuadLayers\WP_Plugin_Suggestions\Table;

/**
 * Load class
 */
class Load {

	/**
	 * Instance
	 *
	 * @var null
	 */
	protected static $instance;

	public $data = [
		"author" => "quadlayers"
	];

	public function __construct() {
		add_filter( 'install_plugins_tabs', array( $this, 'add_tab' ) );
		add_action( 'install_plugins_quadlayers', array( $this, 'add_tab_content' ) );
	}

	/**
	 * Add tab
	 * @param  array $tabs
	 * @return array
	 */
	public function add_tab( $tabs ) {
		$tabs['quadlayers'] = 'QuadLayers';
		return $tabs;
	}

	/**
	 * Add tab content
	 * @return void
	 */
	public function add_tab_content() {
		$wp_list_table = new Table( $this->data );
		$wp_list_table->prepare_items();
		?>
		<form id="plugin-filter" method="post">
			<?php $wp_list_table->display(); ?>
		</form>
		<?php
	}

	/**
	 * Get the instance of the class.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
