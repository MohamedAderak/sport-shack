<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Special_Banner extends Widget_Base {

	public function get_name() {
        return 'rashy_element_special_banner';
    }

	public function get_title() {
        return esc_html__( 'Goal Special Banner', 'rashy' );
    }

	public function get_icon() {
        return 'eicon-image-box';
    }

	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Special Banner', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'rashy' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
                'condition' => [
                    'image_icon' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'title_text',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title', 'rashy' ),
            ]
        );

        $repeater->add_control(
            'products_count',
            [
                'label' => esc_html__( 'Number Count', 'rashy' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link to', 'rashy' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'rashy' ),
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'banners',
            [
                'label' => esc_html__( 'Banners Item', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'animation',
            [
                'label' => esc_html__( 'Animation', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('None', 'rashy'),
                    'fade' => esc_html__('Fade', 'rashy'),
                    'fade-up' => esc_html__('Fade Up', 'rashy'),
                    'fade-down' => esc_html__('Fade Down', 'rashy'),
                    'fade-left' => esc_html__('Fade Left', 'rashy'),
                    'fade-right' => esc_html__('Fade Right', 'rashy'),
                    'fade-up-right' => esc_html__('Fade Up Right', 'rashy'),
                    'fade-up-left' => esc_html__('Fade Up Left', 'rashy'),
                    'fade-down-right' => esc_html__('Fade Down Right', 'rashy'),
                    'fade-down-left' => esc_html__('Fade Down Left', 'rashy'),
                    'flip-up' => esc_html__('Flip Up', 'rashy'),
                    'flip-down' => esc_html__('Flip Down', 'rashy'),
                    'flip-left' => esc_html__('Flip Left', 'rashy'),
                    'flip-right' => esc_html__('Flip Right', 'rashy'),
                    'slide-up' => esc_html__('Slide Up', 'rashy'),
                    'slide-down' => esc_html__('Slide Down', 'rashy'),
                    'slide-left' => esc_html__('Slide Left', 'rashy'),
                    'slide-right' => esc_html__('Slide Right', 'rashy'),
                    'zoom-in' => esc_html__('Zoom In', 'rashy'),
                    'zoom-in-up' => esc_html__('Zoom In Up', 'rashy'),
                    'zoom-in-down' => esc_html__('Zoom In Down', 'rashy'),
                    'zoom-in-left' => esc_html__('Zoom In Left', 'rashy'),
                    'zoom-in-right' => esc_html__('Zoom In Right', 'rashy'),
                    'zoom-out' => esc_html__('Zoom Out', 'rashy'),
                    'zoom-out-up' => esc_html__('Zoom Out Up', 'rashy'),
                    'zoom-out-down' => esc_html__('Zoom Out Down', 'rashy'),
                    'zoom-out-left' => esc_html__('Zoom Out Feft', 'rashy'),
                    'zoom-out-right' => esc_html__('Zoom Out Right', 'rashy')
                ),
                'default' => ''
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'rashy' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'rashy' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'rashy' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'rashy' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'rashy' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
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


        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'rashy' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .title a, {{WRAPPER}} .title',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($banners) ) {
            $rand_id = rashy_random_key();
            ?>
            <div class="widget-banners-box <?php echo esc_attr($el_class); ?>">
                <nav id="txtanimation-<?php echo esc_attr($rand_id); ?>" class="txtcollection text-<?php echo esc_attr($alignment); ?>" data-section-type="textanimation" data-section-id="<?php echo esc_attr($rand_id); ?>" data-animation="<?php echo esc_attr($animation); ?>">
                    <?php foreach ($banners as $item):
                        if ( ! empty( $item['link']['url'] ) ) {
                            echo '<a class="item__collection item_'.esc_attr($rand_id).'" href="'.esc_url($item['link']['url']).'" target="'.esc_attr($item['link']['is_external'] ? '_blank' : '_self').'" '.($item['link']['nofollow'] ? 'rel="nofollow"' : '').'>';
                        } else {
                            echo '<a class="item__collection item_'.esc_attr($rand_id).'" href="#">';
                        }
                    ?>
                            <?php echo wp_get_attachment_image($item['image']['id'], 'full', '', array( 'class' => 'img' )); ?>

                            <span class="h2 item__collection-name d-block"><?php echo esc_attr($item['title_text']); ?></span>
                            <?php if ( !empty($item['products_count']) ) { ?>
                                <span class="item__collection-sub font-family-2 text-uppercase d-block">
                                    <?php echo esc_attr($item['products_count']); ?> 
                                </span>
                            <?php } ?>
                           
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
            <?php
        }
    }

}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Special_Banner );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Special_Banner );
}