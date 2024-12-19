<?php
/**
 * Brand manager for goal framework
 *
 * @package    goal-framework
 * @author     Team Goalthemes <goaltheme@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Goal Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Goal_PostType_Brand {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
    	add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => __( 'Goal Brand', 'goal-framework' ),
			'singular_name'         => __( 'Brand', 'goal-framework' ),
			'add_new'               => __( 'Add New Brand', 'goal-framework' ),
			'add_new_item'          => __( 'Add New Brand', 'goal-framework' ),
			'edit_item'             => __( 'Edit Brand', 'goal-framework' ),
			'new_item'              => __( 'New Brand', 'goal-framework' ),
			'all_items'             => __( 'All Brands', 'goal-framework' ),
			'view_item'             => __( 'View Brand', 'goal-framework' ),
			'search_items'          => __( 'Search Brand', 'goal-framework' ),
			'not_found'             => __( 'No Brands found', 'goal-framework' ),
			'not_found_in_trash'    => __( 'No Brands found in Trash', 'goal-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'goal Brands', 'goal-framework' ),
	    );

	    register_post_type( 'goal_brand',
	      	array(
		        'labels'            => apply_filters( 'goal_postype_brand_labels' , $labels ),
		        'supports'          => array( 'title', 'thumbnail' ),
		        'public'            => true,
		        'has_archive'       => true,
		        'menu_position'     => 54
	      	)
	    );

  	}
  	
  	public static function metaboxes(array $metaboxes){
		$prefix = 'goal_brand_';
	    
	    $metaboxes[ $prefix . 'settings' ] = array(
			'id'                        => $prefix . 'settings',
			'title'                     => __( 'Brand Information', 'goal-framework' ),
			'object_types'              => array( 'goal_brand' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_fields()
		);

	    return $metaboxes;
	}

	public static function metaboxes_fields() {
		$prefix = 'goal_brand_';
	
		$fields =  array(
			array(
				'name' => __( 'Brand Link', 'goal-framework' ),
				'id'   => $prefix."link",
				'type' => 'text'
			)
		);  
		
		return apply_filters( 'goal_framework_postype_goal_brand_metaboxes_fields' , $fields );
	}
}

Goal_PostType_Brand::init();