<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Search_Form extends Elementor\Widget_Base {

    public function get_name() {
        return 'rashy_element_search_form';
    }

    public function get_title() {
        return esc_html__( 'Goal Header Search Form', 'rashy' );
    }
    
    public function get_categories() {
        return [ 'rashy-header-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => esc_html__( 'Show Categories', 'rashy' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Show', 'rashy' ),
                'label_off' => esc_html__( 'Hide', 'rashy' ),
            ]
        );

        $this->add_control(
            'show_auto_search',
            [
                'label' => esc_html__( 'Show Autocomplete Search', 'rashy' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Show', 'rashy' ),
                'label_off' => esc_html__( 'Hide', 'rashy' ),
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'rashy' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Show', 'rashy' ),
                'label_off' => esc_html__( 'Hide', 'rashy' ),
            ]
        );

        $this->add_control(
            'show_text',
            [
                'label' => esc_html__( 'Show text Search', 'rashy' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__( 'Show', 'rashy' ),
                'label_off' => esc_html__( 'Hide', 'rashy' ),
            ]
        );

        $this->add_responsive_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    'style1' => esc_html__( 'Style 1', 'rashy' ),
                    'style2' => esc_html__( 'Style 2', 'rashy' ),
                    'style3' => esc_html__( 'Style 3', 'rashy' ),
                ],
                'default' => 'style1'
            ]
        );

        $this->add_control(
            'quick_links_title',
            [
                'label' => esc_html__( 'Quick Links Title', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title here', 'rashy' ),
                'condition' => [
                    'style' => 'style2',
                ],
            ]
        );

        $custom_menus = array();
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
        if ( is_array( $menus ) && ! empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                if ( is_object( $menu ) && isset( $menu->name, $menu->slug ) ) {
                    $custom_menus[ $menu->slug ] = $menu->name;
                }
            }
        }

        $this->add_control(
            'nav_menu',
            [
                'label' => esc_html__( 'Quick Links Menu', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $custom_menus,
                'default' => '',
                'condition' => [
                    'style' => 'style2',
                ],
            ]
        );

        $this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'rashy' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'rashy' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_box_style',
            [
                'label' => esc_html__( 'Box', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rashy' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__( 'Border Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .goal-search-form-inner form' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .show-search-header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .show-search-header:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .show-search-header:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .goal-search-form-inner form .btn-search' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__( 'Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn-search' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hv_color',
            [
                'label' => esc_html__( 'Color Hover', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn-search:hover, {{WRAPPER}} .btn-search:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        ?>
        
        <div class="goal-search-form <?php echo esc_attr($el_class.' '.$style); ?>">
            <?php if ( $style == 'style2' ) { ?>
                <span class="show-search-header"><i class="ti-search"></i></span>
            <?php } ?>
            <div class="goal-search-form-inner <?php echo esc_attr($style); ?>">
                <?php if ( $style == 'style2' ) { ?>
                    <div class="container">
                        <h3 class="title"><?php esc_html_e('WHAT ARE YOU LOOKING FOR?', 'rashy'); ?></h3>
                <?php } ?>
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                    <?php 
                        if ( $show_categories && rashy_is_woocommerce_activated() ) {
                            $args = array(
                                'show_count' => 0,
                                'hierarchical' => true,
                                'show_uncategorized' => 0
                            );
                            echo '<div class="select-category">';
                                wc_product_dropdown_categories( $args );
                            echo '</div>';
                        }
                    ?>
                    <div class="main-search">
                        <?php if ( $show_auto_search ) echo '<div class="twitter-typeahead">'; ?>
                            <input type="text" placeholder="<?php esc_attr_e( 'Search products...', 'rashy' ); ?>" name="s" class="goal-search form-control <?php echo esc_attr($show_auto_search ? 'goal-autocompleate-input' : ''); ?>" autocomplete="off"/>
                        <?php if ( $show_auto_search ) echo '</div>'; ?>
                    </div>
                    <input type="hidden" name="post_type" value="product" class="post_type" />
                   
                    <button type="submit" class="btn-search <?php echo esc_attr(($show_icon && !$show_text)?'st_small':''); ?>"><?php if($show_icon){ ?><i class="ti-search"></i><?php } ?><?php if($show_text){ ?><span class="text"><?php esc_html_e('Search', 'rashy'); ?></span><?php } ?></button>
                    

                </form>
                <?php if ( $style == 'style2' ) {

                        $menu_id = 0;
                        if ($nav_menu) {
                            $term = get_term_by( 'slug', $nav_menu, 'nav_menu' );
                            if ( !empty($term) ) {
                                $menu_id = $term->term_id;
                            }
                        }
                        ?>
                        <?php if ( !empty($menu_id) ) { ?>
                            <div class="quick-links-wrapper">
                                <?php if ( $quick_links_title ) {
                                    ?>
                                    <h4 class="title-quick-links"><?php echo esc_html($quick_links_title); ?></h4>
                                    <?php
                                }
                                    $nav_menu_args = array(
                                        'fallback_cb' => '',
                                        'menu'        => $menu_id
                                    );

                                    wp_nav_menu( $nav_menu_args, $menu_id );
                                ?>
                            </div>
                        <?php } ?>

                    </div>
                <?php } ?>
            </div>
            <?php if ( $style == 'style2' ) { ?>
                <div class="overlay-search-header"></div>
            <?php } ?>
        </div>
        <?php
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Search_Form );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Search_Form );
}