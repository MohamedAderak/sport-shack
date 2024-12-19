<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Lookbook extends Widget_Base {

	public function get_name() {
        return 'goal_element_woo_lookbook';
    }

	public function get_title() {
        return esc_html__( 'Goal Lookbook', 'rashy' );
    }

    public function get_icon() {
        return 'ti-bag';
    }

	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {
        $posts = get_posts(
            array(
                'post_type' => 'lookbook',
                'number' => -1,
            )
        );
        $pposts = [ '' => esc_html__('Choose a lookbook', 'rashy') ];
        if ( !empty($posts) ) {
            foreach ($posts as $post) {
                $pposts[$post->post_name] = $post->post_title;
            }
        }
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'lookbook',
            [
                'label' => esc_html__( 'Lookbook', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => $pposts,
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Carousel Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'rashy'),
                    'style2' => esc_html__('Style 2', 'rashy'),
                    'style3' => esc_html__('Style 3', 'rashy'),
                ),
                'default' => 'style1',
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'rashy' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'rashy' ),
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($lookbook) ) {
            ?>
            <div class="widget widget-lookbook <?php echo esc_attr($el_class); ?>">
               <?php echo do_shortcode('[lookbook slug="'.$lookbook.'" style="'.$style.'"]'); ?>
            </div>
            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Lookbook );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Lookbook );
}