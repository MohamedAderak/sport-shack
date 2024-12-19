<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Vertical_Menu extends Elementor\Widget_Base {

	public function get_name() {
        return 'rashy_vertical_menu';
    }

	public function get_title() {
        return esc_html__( 'Goal Header Vertical Menu', 'rashy' );
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
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'All Departments'
            ]
        );

        $this->add_responsive_control(
            'show_menu',
            [
                'label' => esc_html__( 'Show menu condition', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    'show-always' => esc_html__( 'Always', 'rashy' ),
                    'show-in-home' => esc_html__( 'In home page', 'rashy' ),
                    'show-hover' => esc_html__( 'When hover', 'rashy' ),
                ],
                'default' => 'show-in-home'
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
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_title_color',
            [
                'label' => esc_html__( 'Background Color Title', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .vertical-wrapper .title-vertical' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .goal-vertical-menu > li > a > i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .goal-vertical-menu > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Link Hover Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .goal-vertical-menu > li:hover > a,{{WRAPPER}} .goal-vertical-menu > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( has_nav_menu( 'vertical-menu' ) ) { ?>
            <div class="vertical-wrapper <?php echo esc_attr($el_class.' '.$show_menu); ?>">
                <h2 class="title-vertical"><i class="fa fa-bars" aria-hidden="true"></i> <span class="text-title"><?php echo wp_kses_post($title); ?></span> <i class="fa fa-angle-down show-down" aria-hidden="true"></i></h2>
                <?php
                    $args = array(
                        'theme_location' => 'vertical-menu',
                        'container_class' => 'content-vertical',
                        'menu_class' => 'goal-vertical-menu nav navbar-nav',
                        'fallback_cb' => '',
                        'menu_id' => 'vertical-menu',
                        'walker' => new Rashy_Nav_Menu()
                    );
                    wp_nav_menu($args);
                ?>
            </div>
        <?php
        }
    }

}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Vertical_Menu );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Vertical_Menu );
}