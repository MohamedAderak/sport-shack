<?php

// Shop Archive settings
function rashy_woo_dokan_redux_config($sections, $sidebars, $columns) {

    // Product Page
    $sections[] = array(
        'title' => esc_html__('Dokan Settings', 'rashy'),
        'fields' => array(
            array(
                'id' => 'dokan_general_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('General Setting', 'rashy').'</h3>',
            ),
            
            array(
                'id' => 'dokan_show_vendor_name',
                'type' => 'switch',
                'title' => esc_html__('Show Vendor Name', 'rashy'),
                'default' => 1
            ),
            
            array(
                'id' => 'dokan_show_more_products',
                'type' => 'switch',
                'title' => esc_html__('Show More Products From This Vendor', 'rashy'),
                'default' => 1
            ),

            array(
                'id' => 'dokan_show_vendor_info',
                'type' => 'switch',
                'title' => esc_html__('Show Vendor Info', 'rashy'),
                'default' => 1
            ),
        )
    );
    
    return $sections;
}
add_filter( 'rashy_redux_framwork_configs', 'rashy_woo_dokan_redux_config', 10, 3 );