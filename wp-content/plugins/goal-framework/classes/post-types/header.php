<?php
/**
 * Header manager for goal framework
 *
 * @package    goal-framework
 * @author     Team Goalthemes <goaltheme@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Goal Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Goal_PostType_Header {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => __( 'Header Builder', 'goal-framework' ),
			'singular_name'         => __( 'Header', 'goal-framework' ),
			'add_new'               => __( 'Add New Header', 'goal-framework' ),
			'add_new_item'          => __( 'Add New Header', 'goal-framework' ),
			'edit_item'             => __( 'Edit Header', 'goal-framework' ),
			'new_item'              => __( 'New Header', 'goal-framework' ),
			'all_items'             => __( 'All Headers', 'goal-framework' ),
			'view_item'             => __( 'View Header', 'goal-framework' ),
			'search_items'          => __( 'Search Header', 'goal-framework' ),
			'not_found'             => __( 'No Headers found', 'goal-framework' ),
			'not_found_in_trash'    => __( 'No Headers found in Trash', 'goal-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Headers Builder', 'goal-framework' ),
	    );

	    register_post_type( 'goal_header',
	      	array(
		        'labels'            => apply_filters( 'goal_postype_header_labels' , $labels ),
		        'supports'          => array( 'title', 'editor' ),
		        'public'            => true,
		        'has_archive'       => false,
		        'show_in_nav_menus' => false,
		        'menu_position'     => 51,
		        'menu_icon'         => 'dashicons-admin-post',
	      	)
	    );

  	}
  
}

Goal_PostType_Header::init();