<?php
/**
 * mentor post type
 *
 * @package    goal-framework
 * @author     GoalTheme <goaltheme@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 GoalTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Goal_PostType_Team{

	/**
	 * init action and filter data to define resource post type
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_action( 'init', array( __CLASS__, 'definition_taxonomy' ) );
		add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
	}
	/**
	 *
	 */
	public static function definition() {
		
		$labels = array(
			'name'                  => __( 'Goal Teams', 'goal-framework' ),
			'singular_name'         => __( 'Team', 'goal-framework' ),
			'add_new'               => __( 'Add New Team', 'goal-framework' ),
			'add_new_item'          => __( 'Add New Team', 'goal-framework' ),
			'edit_item'             => __( 'Edit Team', 'goal-framework' ),
			'new_item'              => __( 'New Team', 'goal-framework' ),
			'all_items'             => __( 'All Teams', 'goal-framework' ),
			'view_item'             => __( 'View Team', 'goal-framework' ),
			'search_items'          => __( 'Search Team', 'goal-framework' ),
			'not_found'             => __( 'No Teams found', 'goal-framework' ),
			'not_found_in_trash'    => __( 'No Teams found in Trash', 'goal-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Goal Teams', 'goal-framework' ),
		);

		$labels = apply_filters( 'goal_framework_postype_mentor_labels' , $labels );

		register_post_type( 'goal_team',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title', 'editor', 'thumbnail' ),
				'public'            => true,
				'has_archive'       => true,
				'rewrite'           => array( 'slug' => __( 'mentor', 'goal-framework' ) ),
				'menu_position'     => 54,
				'categories'        => array(),
				'show_in_menu'  	=> true,
			)
		);
	}

	public static function definition_taxonomy() {
		$labels = array(
			'name'              => __( 'Team Categories', 'goal-framework' ),
			'singular_name'     => __( 'Team Category', 'goal-framework' ),
			'search_items'      => __( 'Search Team Categories', 'goal-framework' ),
			'all_items'         => __( 'All Team Categories', 'goal-framework' ),
			'parent_item'       => __( 'Parent Team Category', 'goal-framework' ),
			'parent_item_colon' => __( 'Parent Team Category:', 'goal-framework' ),
			'edit_item'         => __( 'Edit Team Category', 'goal-framework' ),
			'update_item'       => __( 'Update Team Category', 'goal-framework' ),
			'add_new_item'      => __( 'Add New Team Category', 'goal-framework' ),
			'new_item_name'     => __( 'New Team Category', 'goal-framework' ),
			'menu_name'         => __( 'Team Categories', 'goal-framework' ),
		);

		register_taxonomy( 'goal_team_category', 'goal_team', array(
			'labels'            => apply_filters( 'goal_framework_taxomony_team_category_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'team-category',
			'rewrite'           => array( 'slug' => __( 'team-category', 'goal-framework' ) ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}

	/**
	 *
	 */
	public static function metaboxes( array $metaboxes ) {
		$prefix = 'goal_team_';
		
		$metaboxes[ $prefix . 'info' ] = array(
			'id'                        => $prefix . 'info',
			'title'                     => __( 'More Informations', 'goal-framework' ),
			'object_types'              => array( 'goal_team' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_info_fields()
		);
		
		return $metaboxes;
	}

	public static function metaboxes_info_fields() {
		$prefix = 'goal_team_';
		$fields = array(
			array(
				'name'              => __( 'Job', 'goal-framework' ),
				'id'                => $prefix . 'job',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Facebook', 'goal-framework' ),
				'id'                => $prefix . 'facebook',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Twitter', 'goal-framework' ),
				'id'                => $prefix . 'twitter',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Behance', 'goal-framework' ),
				'id'                => $prefix . 'behance',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Linkedin', 'goal-framework' ),
				'id'                => $prefix . 'linkedin',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Instagram', 'goal-framework' ),
				'id'                => $prefix . 'instagram',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Google Plus', 'goal-framework' ),
				'id'                => $prefix . 'google_plus',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Pinterest', 'goal-framework' ),
				'id'                => $prefix . 'pinterest',
				'type'              => 'text'
			),
		);

		return apply_filters( 'goal_framework_postype_goal_team_metaboxes_fields' , $fields );
	}

}

Goal_PostType_Team::init();