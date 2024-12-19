<?php

if ( !class_exists("Rashy_Woo_Brand") ) {
	class Rashy_Woo_Brand {

		public static function init() {
			// brand
			$tax = rashy_get_config( 'product_brand_attribute' );
			if ( !empty($tax) ) {
				add_filter( "manage_edit-{$tax}_columns", array( __CLASS__, 'brand_columns' ) );
				add_filter( "manage_{$tax}_custom_column", array( __CLASS__, 'brand_column' ), 10, 3 );
				add_action( "{$tax}_add_form_fields", array( __CLASS__, 'add_brand' ) );
				add_action( "{$tax}_edit_form_fields", array( __CLASS__, 'edit_brand' ) );
				add_action( 'create_term', array( __CLASS__, 'save_brand_image' )  );
				add_action( 'edit_term', array( __CLASS__, 'save_brand_image' ) );
			}
		}

		public static function add_brand() {
			?>
			<div class="form-field">
				<label><?php esc_html_e( 'Thumbnail', 'rashy' ); ?></label>
				<?php self::brand_image_field(); ?>
			</div>
			<?php
		}

		public static function edit_brand( $term ) {
			$image = get_woocommerce_term_meta( $term->term_id, 'product_brand_image', true );
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'rashy' ); ?></label></th>
				<td>
					<?php self::brand_image_field($image); ?>
				</td>
			</tr>
			<?php
		}

		public static function brand_image_field( $image = '' ) {
			?>
			<div class="screenshot">
				<?php if ( $image ) { ?>
	                <img src="<?php echo esc_url($image); ?>"/>
	            <?php } ?>
			</div>
			<input type="hidden" id="product_brand_image" name="product_brand_image" value="<?php echo esc_attr( $image ); ?>" class="upload_image" />
			<div class="upload_image_action">
	            <input type="button" class="button add-image" value="<?php esc_html_e( 'Add', 'rashy' ); ?>">
	            <input type="button" class="button remove-image" value="<?php esc_html_e( 'Remove', 'rashy' ); ?>">
	        </div>
			<?php
		}

		public static function save_brand_image( $term_id ) {
			if ( isset($_POST['product_brand_image']) ) {
				update_woocommerce_term_meta( $term_id, 'product_brand_image', $_POST['product_brand_image'] );
			}
			delete_transient( 'wc_term_counts' );
		}

		public static function brand_columns( $columns ) {
			$new_columns = array();
			foreach ($columns as $key => $value) {
				if ( $key == 'name' ) {
					$new_columns['image'] = esc_html__( 'Image', 'rashy' );
				}
				$new_columns[$key] = $value;
			}
			return $new_columns;
		}

		public static function brand_column( $columns, $column, $id ) {
			if ( $column == 'image' ) {
				$image = get_woocommerce_term_meta( $id, 'product_brand_image', true );
				$columns .= '<img src="' . esc_url( $image ) . '" alt="'.esc_attr__( 'Image', 'rashy' ).'" class="wp-post-image" />';
			}

			return $columns;
		}

		public static function get_product_brands() {
		    global $product;
		    $brands_tax = rashy_get_config( 'product_brand_attribute' );
		    $terms = get_the_terms( $product->get_id(), $brands_tax );
		    $brand_html = '';

		    if ( $terms && ! is_wp_error( $terms ) ) {
		    	$i = 0;
		        foreach ( $terms as $term ) {
		            $brand_html  .= '<a href="' . esc_url( get_term_link( $term ) ). '">' . esc_attr( $term->name ) . '</a>'.($i != count($terms - 1) ? ', ' : '');
		            $i++;
		        }
		    }
		    if ( ! empty( $brand_html ) ) { ?>
		        <div class="product-brand">
		            <?php echo trim( $brand_html ); ?>
		        </div>
		    <?php }
		}

		public static function get_brands($number = 8) {
			$brands_tax = rashy_get_config( 'product_brand_attribute' );
			$terms = array();
			if ( $brands_tax ) {
				$terms = get_terms( array(
				    'taxonomy' => $brands_tax,
				    'hide_empty' => false,
				    'number' => $number
				) );
			}
			return $terms;
		}
	}
	add_action( 'init', array('Rashy_Woo_Brand', 'init') );
}