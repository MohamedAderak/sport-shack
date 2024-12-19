<?php
/**
 * Footer manager for goal framework
 *
 * @package    goal-framework
 * @author     Team Goalthemes <goaltheme@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Goal Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Goal_PostType_Footer {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => __( 'Footer Builder', 'goal-framework' ),
			'singular_name'         => __( 'Footer', 'goal-framework' ),
			'add_new'               => __( 'Add New Footer', 'goal-framework' ),
			'add_new_item'          => __( 'Add New Footer', 'goal-framework' ),
			'edit_item'             => __( 'Edit Footer', 'goal-framework' ),
			'new_item'              => __( 'New Footer', 'goal-framework' ),
			'all_items'             => __( 'All Footers', 'goal-framework' ),
			'view_item'             => __( 'View Footer', 'goal-framework' ),
			'search_items'          => __( 'Search Footer', 'goal-framework' ),
			'not_found'             => __( 'No Footers found', 'goal-framework' ),
			'not_found_in_trash'    => __( 'No Footers found in Trash', 'goal-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Footers Builder', 'goal-framework' ),
	    );

	    register_post_type( 'goal_footer',
	      	array(
		        'labels'            => apply_filters( 'goal_postype_footer_labels' , $labels ),
		        'supports'          => array( 'title', 'editor' ),
		        'public'            => true,
		        'has_archive'       => false,
		        'show_in_nav_menus' => false,
		        'menu_position'     => 52,
		        'menu_icon'         => 'dashicons-admin-post',
	      	)
	    );

  	}
  
}

Goal_PostType_Footer::init();