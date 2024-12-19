<?php
/**
 * Testimonial manager for goal framework
 *
 * @package    goal-framework
 * @author     Team Goalthemes <goaltheme@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Goal Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Goal_PostType_Testimonial {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
    	add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => __( 'Goal Testimonial', 'goal-framework' ),
			'singular_name'         => __( 'Testimonial', 'goal-framework' ),
			'add_new'               => __( 'Add New Testimonial', 'goal-framework' ),
			'add_new_item'          => __( 'Add New Testimonial', 'goal-framework' ),
			'edit_item'             => __( 'Edit Testimonial', 'goal-framework' ),
			'new_item'              => __( 'New Testimonial', 'goal-framework' ),
			'all_items'             => __( 'All Testimonials', 'goal-framework' ),
			'view_item'             => __( 'View Testimonial', 'goal-framework' ),
			'search_items'          => __( 'Search Testimonial', 'goal-framework' ),
			'not_found'             => __( 'No Testimonials found', 'goal-framework' ),
			'not_found_in_trash'    => __( 'No Testimonials found in Trash', 'goal-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Goal Testimonials', 'goal-framework' ),
	    );

	    register_post_type( 'goal_testimonial',
	      	array(
		        'labels'            => apply_filters( 'goal_postype_testimonial_labels' , $labels ),
		        'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		        'public'            => true,
		        'has_archive'       => true,
		        'menu_position'     => 54
	      	)
	    );

  	}
  	
  	public static function metaboxes(array $metaboxes){
		$prefix = 'goal_testimonial_';
	    
	    $metaboxes[ $prefix . 'settings' ] = array(
			'id'                        => $prefix . 'settings',
			'title'                     => __( 'Testimonial Information', 'goal-framework' ),
			'object_types'              => array( 'goal_testimonial' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_fields()
		);

	    return $metaboxes;
	}

	public static function metaboxes_fields() {
		$prefix = 'goal_testimonial_';
	
		$fields =  array(
			array(
	            'name' => __( 'Job', 'goal-framework' ),
	            'id'   => "{$prefix}job",
	            'type' => 'text',
	            'description' => __('Enter Job example CEO, CTO','goal-framework')
          	), 
			array(
				'name' => __( 'Testimonial Link', 'goal-framework' ),
				'id'   => $prefix."link",
				'type' => 'text'
			)
		);  
		
		return apply_filters( 'goal_framework_postype_goal_testimonial_metaboxes_fields' , $fields );
	}
}

Goal_PostType_Testimonial::init();