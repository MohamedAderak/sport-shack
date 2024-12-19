<?php

if ( !function_exists( 'rashy_page_metaboxes' ) ) {
	function rashy_page_metaboxes(array $metaboxes) {
		global $wp_registered_sidebars;
        $sidebars = array();

        if ( !empty($wp_registered_sidebars) ) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }
        }
        $headers = array_merge( array('global' => esc_html__( 'Global Setting', 'rashy' )), rashy_get_header_layouts() );
        $footers = array_merge( array('global' => esc_html__( 'Global Setting', 'rashy' )), rashy_get_footer_layouts() );

		$prefix = 'goal_page_';
	    $fields = array(
			array(
				'name' => esc_html__( 'Select Layout', 'rashy' ),
				'id'   => $prefix.'layout',
				'type' => 'select',
				'options' => array(
					'main' => esc_html__('Main Content Only', 'rashy'),
					'left-main' => esc_html__('Left Sidebar - Main Content', 'rashy'),
					'main-right' => esc_html__('Main Content - Right Sidebar', 'rashy')
				)
			),
			array(
                'id' => $prefix.'fullwidth',
                'type' => 'select',
                'name' => esc_html__('Is Full Width?', 'rashy'),
                'default' => 'no',
                'options' => array(
                    'no' => esc_html__('No', 'rashy'),
                    'yes' => esc_html__('Yes', 'rashy')
                )
            ),
            array(
                'id' => $prefix.'left_sidebar',
                'type' => 'select',
                'name' => esc_html__('Left Sidebar', 'rashy'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'right_sidebar',
                'type' => 'select',
                'name' => esc_html__('Right Sidebar', 'rashy'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'show_breadcrumb',
                'type' => 'select',
                'name' => esc_html__('Show Breadcrumb?', 'rashy'),
                'options' => array(
                    'no' => esc_html__('No', 'rashy'),
                    'yes' => esc_html__('Yes', 'rashy')
                ),
                'default' => 'yes',
            ),
            array(
                'id' => $prefix.'breadcrumb_color',
                'type' => 'colorpicker',
                'name' => esc_html__('Breadcrumb Background Color', 'rashy')
            ),
            array(
                'id' => $prefix.'breadcrumb_image',
                'type' => 'file',
                'name' => esc_html__('Breadcrumb Background Image', 'rashy')
            ),
            array(
                'id' => $prefix.'header_type',
                'type' => 'select',
                'name' => esc_html__('Header Layout Type', 'rashy'),
                'description' => esc_html__('Choose a header for your website.', 'rashy'),
                'options' => $headers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'header_transparent',
                'type' => 'select',
                'name' => esc_html__('Header Transparent', 'rashy'),
                'description' => esc_html__('Choose a header for your website.', 'rashy'),
                'options' => array(
                    'no' => esc_html__('No', 'rashy'),
                    'yes' => esc_html__('Yes', 'rashy')
                ),
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'footer_type',
                'type' => 'select',
                'name' => esc_html__('Footer Layout Type', 'rashy'),
                'description' => esc_html__('Choose a footer for your website.', 'rashy'),
                'options' => $footers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'extra_class',
                'type' => 'text',
                'name' => esc_html__('Extra Class', 'rashy'),
                'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'rashy')
            )
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'Display Settings', 'rashy' ),
			'object_types'              => array( 'page' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'rashy_page_metaboxes' );

if ( !function_exists( 'rashy_cmb2_style' ) ) {
	function rashy_cmb2_style() {
		wp_enqueue_style( 'rashy-cmb2-style', get_template_directory_uri() . '/inc/vendors/cmb2/assets/style.css', array(), '1.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'rashy_cmb2_style' );


