<?php
/**
 * shortcode loader
 *
 * @package    goal-lookbook
 * @author     GoalThemes <goalthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 GoalThemes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class GoalLookbook_Shortcode {

	public static function init() {
		add_shortcode( 'lookbook' , array(__CLASS__, 'render_lookbook') );
	}

	public static function render_lookbook($atts) {
		echo GoalLookbook_Template_Loader::get_template_part( 'lookbook', $atts );
	}
}

GoalLookbook_Shortcode::init();