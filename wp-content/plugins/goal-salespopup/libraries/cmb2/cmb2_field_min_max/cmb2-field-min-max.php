<?php

/**
 * Class CMB2_Field_Min_Max
 */
class CMB2_Field_Min_Max {

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	public function __construct() {
		add_filter( 'cmb2_render_min_max', array( $this, 'render_min_max' ), 10, 5 );
		add_filter( 'cmb2_sanitize_min_max', array( $this, 'sanitize_min_max' ), 10, 4 );
	}

	/**
	 * Render field
	 */
	public function render_min_max( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		echo '<div class="min-max-wrapper">';

		$checkbox_args = array(
			'type'       => 'checkbox',
			'name'       => $field->args( '_name' ) . '[enable]',
			'id'       => $field->args( '_name' ) . '_enable',
			'value'      => 'on',
			'desc'       => sprintf(__('Enable %s', 'wp-salespopup'), $field->args( 'name' )),
		);
		
		if ( !empty( $field_escaped_value['enable'] ) ) {
			$checkbox_args['checked'] = 'checked';
		}
		echo $field_type_object->input($checkbox_args);

		echo $field_type_object->input( array(
			'type'       => 'text',
			'name'       => $field->args( '_name' ) . '[min]',
			'id'       => $field->args( '_name' ) . '_min',
			'value'      => isset( $field_escaped_value['min'] ) ? $field_escaped_value['min'] : '',
			'class'       => 'cmb2-text-small',
			'placeholder' => __('Min', 'wp-salespopup'),
		) );
		echo $field_type_object->input( array(
			'type'       => 'text',
			'name'       => $field->args( '_name' ) . '[max]',
			'id'       => $field->args( '_name' ) . '_max',
			'value'      => isset( $field_escaped_value['max'] ) ? $field_escaped_value['max'] : '',
			'class'       => 'cmb2-text-small',
			'placeholder' => __('Max', 'wp-salespopup'),
		) );
		echo '</div>';

		$field_type_object->_desc( true, true );
	}

	/**
	 * Optionally save the min/max values into two custom fields
	 */
	public function sanitize_min_max( $override_value, $value, $object_id, $field_args ) {
		if ( isset( $field_args['split_values'] ) && $field_args['split_values'] ) {
			
			if ( ! empty( $value['enable'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_enable', $value['enable'] );
			} else {
				update_post_meta( $object_id, $field_args['id'] . '_enable', '' );
			}

			if ( ! empty( $value['min'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_min', $value['min'] );
			}

			if ( ! empty( $value['max'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_max', $value['max'] );
			}
		}

		return $value;
	}

}
$cmb2_field_min_max = new CMB2_Field_Min_Max();
