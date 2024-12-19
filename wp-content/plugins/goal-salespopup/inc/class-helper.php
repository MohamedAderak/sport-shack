<?php
/**
 * helper loader
 *
 * @package    goal-salespopup
 * @author     GoalThemes <goalthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 GoalThemes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class GoalSalespopup_Helper {
	public static function get_sales_popup_data() {
		$settings = goal_salespopup_get_settings();

		if ( !empty($settings['multiple_address']) ) {
			$multiple_address = $settings['multiple_address'];
			$multiple_address = array_filter(explode("\n", str_replace("\r", "", $multiple_address)));
			$settings['multiple_address'] = $multiple_address;
		}
		$buy_times = array();
		if ( !empty($settings['time_in_second']['enable']) && $settings['time_in_second']['enable'] == 'on' ) {
			$buy_times[] = array(
				'unit' => 'second',
				'min' => $settings['time_in_second']['min'],
				'max' => $settings['time_in_second']['max'],
			);
		}
		if ( !empty($settings['time_in_minutes']['enable']) && $settings['time_in_minutes']['enable'] == 'on' ) {
			$buy_times[] = array(
				'unit' => 'minute',
				'min' => $settings['time_in_minutes']['min'],
				'max' => $settings['time_in_minutes']['max'],
			);
		}
		if ( !empty($settings['time_in_hours']['enable']) && $settings['time_in_hours']['enable'] == 'on' ) {
			$buy_times[] = array(
				'unit' => 'hour',
				'min' => $settings['time_in_hours']['min'],
				'max' => $settings['time_in_hours']['max'],
			);
		}
		if ( !empty($settings['time_in_days']['enable']) && $settings['time_in_days']['enable'] == 'on' ) {
			$buy_times[] = array(
				'unit' => 'day',
				'min' => $settings['time_in_days']['min'],
				'max' => $settings['time_in_days']['max'],
			);
		}
		$settings['buy_times'] = $buy_times; 

		if ( wp_is_mobile() && $settings['disable_on_mobile'] == 'on' ) {
			$settings['enable_sales_popup'] = 'off';
		}

		$products_ids = goal_salespopup_get_option('products');
		if ( ! empty( $products_ids ) ) {
			$products = array();
			foreach ( $products_ids as $product_id ) {
				$_product = wc_get_product( $product_id );
				if ( ! $_product ) {
					continue;
				}
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'thumbnail' );
				$img_url = '';
				if ( !empty($img[0]) ) {
					$img_url = $img[0];
				}
				$products[] = array(
					'product_name' => $_product->get_name(),
					'price_html'   => $_product->get_price_html(),
					'url'          => get_permalink( $product_id ),
					'img'          => $img_url
				);
			}

			$settings['products'] = $products;
		}

		return $settings;
	}
}