<?php

if ( !class_exists("Rashy_Woo_Swatches") ) {
	class Rashy_Woo_Swatches {

		public static function swatches_list( $image_size = 'woocommerce_thumbnail' ) {

			$show_swatches = rashy_get_config( 'show_product_swatches_on_grid' );
			if ( !$show_swatches || !rashy_is_woo_swatches_activated() ) {
				return;
			}

			global $product;
			$product_id = $product->get_id();

			if ( empty( $product_id ) || ! $product->is_type( 'variable' ) ) {
				return;
			}

			$available_variations = $product->get_available_variations();

			$attribute_name = rashy_get_config('product_swatches_attribute');

			if ( empty( $available_variations ) || empty($attribute_name) ) {
				return;
			}
			$terms = wc_get_product_terms( $product_id, $attribute_name, array( 'fields' => 'all' ) );
			if ( empty($terms) ) {
				return;
			}
			$swatches_to_show = array();
			foreach ($terms as $term) {
				$swatches_to_show[$term->slug] = self::get_option_variations(  $attribute_name, $available_variations, $term, $product_id, $image_size );
			}
			if ( empty( $swatches_to_show ) ) {
				return;
			}

			$output = '<ul class="swatches-wrapper">';
			foreach ($swatches_to_show as $key => $data) {
				if ( !empty($data) ) {
					$output .= '<li>';
					$output .= '<a href="javascript:void(0)" class="'.esc_attr($data['swatches_type']).'" data-image_src="'.esc_attr($data['image_src']).'" data-image_srcset="'.esc_attr($data['image_srcset']).'" data-image_sizes="'.esc_attr($data['image_sizes']).'">';
						switch ( $data['swatches_type'] ) {
							case 'color':
								$output .= sprintf( '<div class="swatch-preview swatch-color" style="background-color:%s;width:16px;height:16px;" data-toggle="tooltip" title="%s"></div>', esc_attr( $data['swatches_value'] ), esc_attr( $data['title'] ) );
								break;

							case 'image':
								$image = $data['swatches_value'] ? wp_get_attachment_image_src( $data['swatches_value'] ) : '';
								$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
								$output .= sprintf( '<img class="swatch-preview swatch-image" src="%s" data-toggle="tooltip" title="%s">', esc_url( $image ), esc_attr( $data['title'] ) );
								break;

							case 'label':
								$output .= sprintf( '<div class="swatch-preview swatch-label"  data-toggle="tooltip" title="%s">%s</div>', esc_html( $data['swatches_value'] ), esc_attr( $data['title'] ) );
								break;
						}
					$output .= '</a>';
					$output .= '</li>';
				}
			}
			$output .= '</ul>';

			echo apply_filters( 'rashy_swatches_list', $output, $product_id, $product );
		}

		public static function get_option_variations( $attribute_name, $available_variations, $term, $product_id = false, $image_size = 'woocommerce_thumbnail' ) {
			$swatches_to_show = array();

			foreach ($available_variations as $key => $variation) {
				$option_variation = array();
				$attr_key = 'attribute_' . $attribute_name;
				if( ! isset( $variation['attributes'][$attr_key] )) {
					return;
				}

				$val = $variation['attributes'][$attr_key];
				if ( $val == $term->slug ) {
					
					if( ! empty( $variation['image_id'] ) ) {
						$src = wp_get_attachment_image_src( $variation['image_id'], $image_size );

						$option_variation = array(
							'variation_id' => $variation['variation_id'],
							'image_src' => $src[0],
							'image_srcset' => function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $variation['image_id'], $image_size ) : false,
							'image_sizes' => function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $variation['image_id'], $image_size ) : false,
							'is_in_stock' => $variation['is_in_stock'],
							'title' => $term->name
						);
					}
					// Or get all variations with swatches to show by attribute name
					
					$attr  = self::get_tax_attribute( $attribute_name );
					$value = get_term_meta( $term->term_id, $attr->attribute_type, true );

					$option_variation['swatches_type'] = $attr->attribute_type;
					$option_variation['swatches_value'] = $value;
					return $option_variation;
				}
			}
		}

		public static function get_tax_attribute( $taxonomy ) {
			global $wpdb;
			$attr = substr( $taxonomy, 3 );
		 	$attr = $wpdb->get_row( $wpdb->prepare(
		        "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name=%s",
		            $attr
		        ));

			return $attr;
		}
	}
}