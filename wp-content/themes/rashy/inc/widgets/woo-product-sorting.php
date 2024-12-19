<?php


if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists('WC_Widget') ) {
	return;
}

class Rashy_Widget_Woo_Product_Sorting extends WC_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    	= 'goal_widget goal_widget_product_sorting woocommerce';
		$this->widget_description	= esc_html__( 'Display a product sorting list.', 'rashy' );
		$this->widget_id          	= 'goal_woocommerce_widget_product_sorting';
		$this->widget_name        	= esc_html__( 'Goal WooCommerce Product Sorting', 'rashy' );
		$this->settings           	= array(
			'title'  => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Sort By', 'rashy' ),
				'label'	=> esc_html__( 'Title', 'rashy' )
			)
		);
		
		parent::__construct();
	}

	public function getTemplate() {
		return 'woo-product-sorting.php';
	}
	/**
	 * Widget function
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {
		global $wp_query;
		
		extract( $args );
		
		$title = ( ! empty( $instance['title'] ) ) ? $before_title . $instance['title'] . $after_title : '';
		
		$output = '';
		
		if ( 1 != $wp_query->found_posts || woocommerce_products_will_display() ) {
			$output .= '<div class="wrapper-limit">';
			$output .= '<ul id="goal-product-sorting" class="goal-product-sorting">';
			
			$orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			$orderby == ( $orderby ===  'title' ) ? 'menu_order' : $orderby; // Fixed: 'title' is default before WooCommerce settings are saved
			
			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order'	=> esc_html__( 'Default', 'rashy' ),
				'popularity' 	=> esc_html__( 'Popularity', 'rashy' ),
				'rating'     	=> esc_html__( 'Average rating', 'rashy' ),
				'date'       	=> esc_html__( 'Newness', 'rashy' ),
				'price'      	=> esc_html__( 'Price: Low to High', 'rashy' ),
				'price-desc'	=> esc_html__( 'Price: High to Low', 'rashy' )
			) );
	
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				unset( $catalog_orderby_options['rating'] );
			}
			
			
			/* Build entire current page URL (including query strings) */
			global $wp;
			$link = home_url( $wp->request ); // Base page URL
					
			// Unset query strings used for Ajax shop filters
			unset( $_GET['shop_load'] );
			unset( $_GET['_'] );
			
			$qs_count = count( $_GET );
			
			// Any query strings to add?
			if ( $qs_count > 0 ) {
				$i = 0;
				$link .= '?';
				
				// Build query string
				foreach ( $_GET as $key => $value ) {
					$i++;
					$link .= $key . '=' . $value;
					if ( $i != $qs_count ) {
						$link .= '&';
					}
				}
			}
			
			
            foreach ( $catalog_orderby_options as $id => $name ) {
				if ( $orderby == $id ) {
					$output .= '<li class="active">' . esc_attr( $name ) . '</li>';
				} else {
					// Add 'orderby' URL query string
					$link = add_query_arg( 'orderby', $id, $link );
					$output .= '<li><a href="' . esc_url( $link ) . '">' . esc_attr( $name ) . '</a></li>';
				}
            }
			       
        	$output .= '</ul>';
        	$output .= '</div>';
		}
		
		echo trim($before_widget . $title . $output . $after_widget);
	}
	
}
if ( function_exists('goal_framework_reg_widget') ) {
    goal_framework_reg_widget('Rashy_Widget_Woo_Product_Sorting');
}