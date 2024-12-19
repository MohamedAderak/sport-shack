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
class Goal_PostType_Portfolio{

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
			'name'                  => __( 'Goal Portfolios', 'goal-framework' ),
			'singular_name'         => __( 'Portfolio', 'goal-framework' ),
			'add_new'               => __( 'Add New Portfolio', 'goal-framework' ),
			'add_new_item'          => __( 'Add New Portfolio', 'goal-framework' ),
			'edit_item'             => __( 'Edit Portfolio', 'goal-framework' ),
			'new_item'              => __( 'New Portfolio', 'goal-framework' ),
			'all_items'             => __( 'All Portfolios', 'goal-framework' ),
			'view_item'             => __( 'View Portfolio', 'goal-framework' ),
			'search_items'          => __( 'Search Portfolio', 'goal-framework' ),
			'not_found'             => __( 'No Portfolios found', 'goal-framework' ),
			'not_found_in_trash'    => __( 'No Portfolios found in Trash', 'goal-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Goal Portfolios', 'goal-framework' ),
		);

		$labels = apply_filters( 'goal_framework_postype_mentor_labels' , $labels );

		register_post_type( 'goal_portfolio',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				'public'            => true,
				'has_archive'       => true,
				'rewrite'           => array( 'slug' => __( 'portfolio', 'goal-framework' ) ),
				'menu_position'     => 54,
				'categories'        => array(),
				'show_in_menu'  	=> true,
			)
		);
	}

	public static function definition_taxonomy() {
		$labels = array(
			'name'              => __( 'Portfolio Categories', 'goal-framework' ),
			'singular_name'     => __( 'Portfolio Category', 'goal-framework' ),
			'search_items'      => __( 'Search Portfolio Categories', 'goal-framework' ),
			'all_items'         => __( 'All Portfolio Categories', 'goal-framework' ),
			'parent_item'       => __( 'Parent Portfolio Category', 'goal-framework' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:', 'goal-framework' ),
			'edit_item'         => __( 'Edit Portfolio Category', 'goal-framework' ),
			'update_item'       => __( 'Update Portfolio Category', 'goal-framework' ),
			'add_new_item'      => __( 'Add New Portfolio Category', 'goal-framework' ),
			'new_item_name'     => __( 'New Portfolio Category', 'goal-framework' ),
			'menu_name'         => __( 'Portfolio Categories', 'goal-framework' ),
		);

		register_taxonomy( 'goal_portfolio_category', 'goal_portfolio', array(
			'labels'            => apply_filters( 'goal_framework_taxomony_portfolio_category_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'portfolio-category',
			'rewrite'           => array( 'slug' => __( 'portfolio-category', 'goal-framework' ) ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}

	/**
	 *
	 */
	public static function metaboxes( array $metaboxes ) {
		$prefix = 'goal_portfolio_';
		
		$metaboxes[ $prefix . 'info' ] = array(
			'id'                        => $prefix . 'info',
			'title'                     => __( 'More Informations', 'goal-framework' ),
			'object_types'              => array( 'goal_portfolio' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_info_fields()
		);
		
		$metaboxes[ $prefix . 'social' ] = array(
			'id'                        => $prefix . 'social',
			'title'                     => __( 'Social Informations', 'goal-framework' ),
			'object_types'              => array( 'goal_portfolio' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_social_fields()
		);

		$metaboxes[ $prefix . 'team' ] = array(
			'id'                        => $prefix . 'team',
			'title'                     => __( 'Team Informations', 'goal-framework' ),
			'object_types'              => array( 'goal_portfolio' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_team_fields()
		);

		return $metaboxes;
	}

	public static function metaboxes_info_fields() {
		$prefix = 'goal_portfolio_';
		$fields = array(
			array(
				'name'              => __( 'Client', 'goal-framework' ),
				'id'                => $prefix . 'client',
				'type'              => 'text'
			),
			array(
				'name'              => __( 'Date', 'goal-framework' ),
				'id'                => $prefix . 'date',
				'type'              => 'text_date'
			),
			array(
				'name'              => __( 'Service', 'goal-framework' ),
				'id'                => $prefix . 'service',
				'type'              => 'textarea'
			),
			array(
				'name' => __( 'Gallery', 'goal-framework' ),
				'id'   => $prefix . 'gallery',
				'type' => 'file_list',
				'query_args' => array( 'type' => 'image' ),
			)
		);

		return apply_filters( 'goal_framework_postype_goal_portfolio_metaboxes_fields' , $fields );
	}

	public static function metaboxes_social_fields() {
		$prefix = 'goal_portfolio_';
		$fields = array(
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

		return apply_filters( 'goal_framework_postype_goal_portfolio_metaboxes_social_fields' , $fields );
	}

	public static function metaboxes_team_fields() {
		$prefix = 'goal_portfolio_';
		$fields =  array(
			array(
				'id'                => $prefix . 'teams',
				'type'              => 'group',
				'options'     => array(
					'group_title'   => esc_html__( 'Member {#}', 'goal-framework' ),
					'add_button'    => esc_html__( 'Add Another Member', 'goal-framework' ),
					'remove_button' => esc_html__( 'Remove Member', 'goal-framework' ),
					'sortable'      => true,
				),
				'fields'            => array(
					array(
						'id'                => 'value',
						'name'              => esc_html__( 'Name', 'goal-framework' ),
						'type'              => 'text',
					),
					array(
						'id'                => 'text',
						'name'              => esc_html__( 'Job', 'goal-framework' ),
						'type'              => 'text',
					),
				),
			),
		);  

		return apply_filters( 'goal_framework_postype_goal_portfolio_metaboxes_team_fields' , $fields );
	}
}

Goal_PostType_Portfolio::init();