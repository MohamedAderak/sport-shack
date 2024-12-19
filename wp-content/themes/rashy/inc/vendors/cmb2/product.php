<?php

if ( !function_exists( 'rashy_product_metaboxes' ) ) {
	function rashy_product_metaboxes(array $metaboxes) {
		$prefix = 'goal_product_';
        $headers = array_merge( array('global' => esc_html__( 'Global Setting', 'rashy' )), rashy_get_header_layouts() );
	    $fields = array(
            array(
                'id' => $prefix.'header_type',
                'type' => 'select',
                'name' => esc_html__('Header Layout Type', 'rashy'),
                'options' => $headers
            ),
	    	array(
                'id' => $prefix.'layout_type',
                'type' => 'select',
                'name' => esc_html__('Layout Type', 'rashy'),
                'options' => array(
                    '' => esc_html__('Global Settings', 'rashy'),
                    'v1' => esc_html__('Layout 1', 'rashy'),
                    'v2' => esc_html__('Layout 2', 'rashy'),
                    'v3' => esc_html__('Layout 3', 'rashy'),
                    'v4' => esc_html__('Layout 4', 'rashy'),
                    'v5' => esc_html__('Layout 5', 'rashy'),
                    'v6' => esc_html__('Layout 6', 'rashy'),
                    'v7' => esc_html__('Layout 7', 'rashy'),
                )
            ),
            array(
                'id' => $prefix.'bg_color',
                'type' => 'colorpicker',
                'name' => esc_html__('Background Color', 'rashy'),
                'description' => esc_html__('For Layout 2 ', 'rashy'),
            ),
	    	array(
  				'name' => esc_html__( 'Review Video', 'rashy' ),
  				'id'   => $prefix.'review_video',
  				'type' => 'text',
  				'description' => esc_html__( 'You can enter a video youtube or vimeo', 'rashy' ),
  			),
        array(
          'name' => esc_html__( 'Features', 'rashy' ),
          'id'   => $prefix.'features',
          'type' => 'wysiwyg'
        ),
    	);
		
		// if ( rashy_is_sizeguides_activated() ) {
		// 	$fields[] = array(
  //               'id' => $prefix.'sizeguides_enable',
  //               'type' => 'select',
  //               'name' => esc_html__('Size Guides Enable', 'rashy'),
  //               'options' => array(
  //                   '' => esc_html__('Global Settings', 'rashy'),
  //                   'enable' => esc_html__('Enable', 'rashy'),
  //                   'disable' => esc_html__('Disable', 'rashy'),
  //               )
  //           );
		// }

	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'More Information', 'rashy' ),
			'object_types'              => array( 'product' ),
			'context'                   => 'normal',
			'priority'                  => 'low',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'rashy_product_metaboxes' );
