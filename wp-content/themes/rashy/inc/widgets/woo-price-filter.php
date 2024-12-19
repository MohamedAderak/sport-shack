<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists('WC_Widget') ) {
	return;
}

class Rashy_Widget_Woo_Price_Filter extends WC_Widget {

	public function __construct() {
		$this->widget_cssclass    = 'goal_widget goal_widget_price_filter woocommerce goal-widget_price_filter';
		$this->widget_description = esc_html__( 'Shows a price filter list in a widget which lets you narrow down the list of shown products when viewing product categories.', 'rashy' );
		$this->widget_id          = 'goal_woocommerce_price_filter';
		$this->widget_name        = esc_html__( 'Goal WooCommerce Price Filter List', 'rashy' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Filter by price', 'rashy' ),
				'label' => esc_html__( 'Title', 'rashy' )
			),
			'price_range_size' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 50,
				'label' => esc_html__( 'Price range size', 'rashy' )
			),
			'max_price_ranges' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 10,
				'label' => esc_html__( 'Max price ranges', 'rashy' )
			),
			'hide_empty_ranges' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => esc_html__( 'Hide empty price ranges', 'rashy' )
			)
		);
		
		parent::__construct();
	}

	public function getTemplate() {
		return 'woo-price-filter.php';
	}
	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $wp, $wp_the_query;
		extract( $args );

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}

		if ( ! $wp_the_query->post_count ) {
			return;
		}

		$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
		$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
		
		$title  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		if ( get_option( 'permalink_structure' ) == '' ) {
			$link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$link = preg_replace( '%\/page/[0-9]+%', '', home_url( $wp->request ) );
		}

		if ( get_search_query() ) {
			$link = add_query_arg( 's', get_search_query(), $link );
		}

		if ( ! empty( $_GET['post_type'] ) ) {
			$link = add_query_arg( 'post_type', esc_attr( $_GET['post_type'] ), $link );
		}

		if ( ! empty ( $_GET['product_cat'] ) ) {
			$link = add_query_arg( 'product_cat', esc_attr( $_GET['product_cat'] ), $link );
		}

		if ( ! empty( $_GET['product_tag'] ) ) {
			$link = add_query_arg( 'product_tag', esc_attr( $_GET['product_tag'] ), $link );
		}

		if ( ! empty( $_GET['orderby'] ) ) {
			$link = add_query_arg( 'orderby', esc_attr( $_GET['orderby'] ), $link );
		}
		$fields = '';
		if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
			foreach ( $_chosen_attributes as $attribute => $data ) {
				$taxonomy_filter = 'filter_' . str_replace( 'pa_', '', $attribute );

				$fields .= '<input type="hidden" name="' . esc_attr( $taxonomy_filter ) . '" value="' . esc_attr( implode( ',', $data['terms'] ) ) . '" />';

				if ( 'or' == $data['query_type'] ) {
					$fields .= '<input type="hidden" name="' . esc_attr( str_replace( 'pa_', 'query_type_', $attribute ) ) . '" value="or" />';
				}
			}
		}
		
		$prices = $this->get_filtered_price();
		$min    = floor( $prices->min_price );
		$max    = ceil( $prices->max_price );

		if ( $min == $max ) {
			return;
		}

		echo trim($before_widget . $before_title . $title . $after_title);
		
		$count = 0;
		$range_size = intval( $instance['price_range_size'] );
		$max_ranges = ( intval( $instance['max_price_ranges'] ) - 1 );
		
		
		$output = '<div class="wrapper-limit">';
		$output .= '<ul class="goal-price-filter">';
		        
		if ( strlen( $min_price ) > 0 ) {
			$output .= '<li><a href="' . esc_url( $link ) . '">' . esc_html__( 'All Prices', 'rashy' ) . '</a></li>';
		} else {
            $output .= '<li class="current">' . esc_html__( 'All Prices', 'rashy' ) . '</li>';
		}
		
		for ( $range_min = 0; $range_min < ( $max + $range_size ); $range_min += $range_size ) {
			$range_max = $range_min + $range_size;
			
			// Hide empty price ranges?
			if ( intval( $instance['hide_empty_ranges'] ) ) {
				if ( $min > $range_max || ( $max + $range_size ) < $range_max ) {
					continue;
				}
			}
			
			$count++;
			
			$min_price_output = wc_price( $range_min );
			
			if ( $count == $max_ranges ) {
				$price_output = $min_price_output . '+';
				
				if ( $range_min != $min_price ) {
					$url = add_query_arg( array( 'min_price' => $range_min, 'max_price' => $max ), $link );
					$output .= '<li><a href="' . esc_url( $url ) . '">' . $price_output . '</a></li>';
				} else {
					$output .= '<li class="current">' . $price_output . '</li>';
				}
				
				break;
			} else {
				$price_output = $min_price_output . ' - ' . wc_price( $range_min + $range_size );
				
				if ( $range_min != $min_price || $range_max != $max_price ) {
					$url = add_query_arg( array( 'min_price' => $range_min, 'max_price' => $range_max ), $link );
					$output .= '<li><a href="' . esc_url( $url ) . '">' . $price_output . '</a></li>';
				} else {
					$output .= '<li class="current">' . $price_output . '</li>';
				}
			}
		}
		
		echo trim($output) . '</ul></div>';
		echo trim($after_widget);
	}

	protected function get_filtered_price() {
		global $wpdb, $wp_the_query;

		$args       = $wp_the_query->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( CAST( price_meta.meta_value AS UNSIGNED ) ) as min_price, max( CAST( price_meta.meta_value AS UNSIGNED ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		return $wpdb->get_row( $sql );
	}
}
if ( function_exists('goal_framework_reg_widget') ) {
    goal_framework_reg_widget('Rashy_Widget_Woo_Price_Filter');
}