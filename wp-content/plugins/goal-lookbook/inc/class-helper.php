<?php
/**
 * helper loader
 *
 * @package    goal-lookbook
 * @author     GoalThemes <goalthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 GoalThemes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class GoalLookbook_Helper {

	public static function includes( $path, $ifiles=array() ) {
	    if ( !empty($ifiles) ) {
	         foreach ( $ifiles as $key => $file ) {
	            $file  = $path.'/'.$file; 
	            if (is_file($file)) {
	                require($file);
	            }
	         }   
	    } else {
	        $files = glob($path);
	        foreach ($files as $key => $file) {
	            if (is_file($file)) {
	                require($file);
	            }
	        }
	    }
	}

	public static function getPost($atts) {
		if (empty($atts) || !isset($atts['slug']) || empty($atts['slug']) ) {
			return;
		}
		if (!isset($atts['post_type']) || empty($atts['post_type'])) {
			$post_type = 'product';
		} else {
			$post_type = $atts['post_type'];
		}
		$args = array(
			'name' => $atts['slug'],
			'post_type' => $post_type,
			'post_status' => 'publish',
  			'posts_per_page' => 1
		);
		$posts = get_posts( $args );
		if (!empty($posts)) {
			return $posts[0];
		}
		return;
	}
}