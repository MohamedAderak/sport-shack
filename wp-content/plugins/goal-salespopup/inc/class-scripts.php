<?php
/**
 * Scripts
 *
 * @package    goal-salespopup
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class GoalSalespopup_Scripts {
	/**
	 * Initialize scripts
	 *
	 * @access public
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend' ) );
	}

	/**
	 * Loads front files
	 *
	 * @access public
	 * @return void
	 */
	public static function enqueue_frontend() {
		
		wp_register_script( 'goal-salespopup-script', GOALSALESPOPUP_PLUGIN_URL . 'assets/script.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'goal-salespopup-style', GOALSALESPOPUP_PLUGIN_URL . 'assets/style.css', array(), '1.0.0' );

		$sales_popup_data = GoalSalespopup_Helper::get_sales_popup_data();
			
		$opts = array(
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			'datas' => $sales_popup_data,
			'text'             => array(
				'second'  => esc_html__( 'second', 'goal-salespopup' ),
				'seconds' => esc_html__( 'seconds', 'goal-salespopup' ),
				'minute'  => esc_html__( 'minute', 'goal-salespopup' ),
				'minutes' => esc_html__( 'minutes', 'goal-salespopup' ),
				'hour'    => esc_html__( 'hour', 'goal-salespopup' ),
				'hours'   => esc_html__( 'hours', 'goal-salespopup' ),
				'day'     => esc_html__( 'day', 'goal-salespopup' ),
				'days'    => esc_html__( 'days', 'goal-salespopup' ),
			)
		);

		wp_localize_script( 'goal-salespopup-script', 'goal_salespopup_opts', $opts );
		wp_enqueue_script( 'goal-salespopup-script' );
	}

}

GoalSalespopup_Scripts::init();
