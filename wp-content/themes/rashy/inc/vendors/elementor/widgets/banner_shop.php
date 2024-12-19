<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Banner_Shop extends Widget_Base {

	public function get_name() {
        return 'rashy_element_banner_shop';
    }

	public function get_title() {
        return esc_html__( 'Goal Banner Shop', 'rashy' );
    }
    
	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Banner', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'rashy' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-banner-shop' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image Top', 'rashy' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Background Image', 'rashy' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'rashy' ),
            ]
        );


        $this->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'rashy' ),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Enter your description here', 'rashy' ),
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your button text here', 'rashy' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'URL', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'rashy' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'rashy'),
                    'style2' => esc_html__('Style 2', 'rashy'),
                ),
                'default' => 'style1'
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
            'section_box',
            [
                'label' => esc_html__( 'Box', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'rashy' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .content-banner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .title ' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'title_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .content-banner:hover .title ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .title',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_description',
            [
                'label' => esc_html__( 'Description', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'description_color',
                [
                    'label' => esc_html__( 'Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .description ' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'description_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .content-banner:hover .description ' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'description_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-banner-shop .content-banner-shop .inner ' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'description_typography',
                    'selector' => '{{WRAPPER}} .description',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('style_tabs');

                $this->start_controls_tab(
                    'style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'rashy' ),
                    ]
                );

                $this->add_control(
                    'button_color',
                    [
                        'label' => esc_html__( 'Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .btn-shop ' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_control(
                    'button_bg_color',
                    [
                        'label' => esc_html__( 'Bg Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .btn-shop ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'rashy' ),
                    ]
                );

                $this->add_control(
                    'button_hv_color',
                    [
                        'label' => esc_html__( 'Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .btn-shop ' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_control(
                    'button_hv_bg_color',
                    [
                        'label' => esc_html__( 'Bg Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .btn-shop:hover ' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .btn-shop:focus ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'button_typography',
                    'selector' => '{{WRAPPER}} .btn-shop',
                ]
            );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-banner-shop <?php echo esc_attr($el_class); ?>">
            <?php if ( !empty($link) ) { ?>
                <a href="<?php echo esc_url($link); ?>">
            <?php } ?>

                <div class="content-banner-shop <?php echo esc_attr($style); ?>">

                    <?php
                    if ( !empty($img_src['id']) ) {
                    ?>
                        <div class="img-banner">
                            <?php echo rashy_get_attachment_thumbnail($img_src['id'], 'full'); ?>
                        </div>
                    <?php } ?>
                    <div class="inner">
                        <?php if ( $title ) { ?>
                            <h3 class="title"><?php echo trim($title); ?></h3>
                        <?php } ?>

                        <?php if ( !empty($description) ) { ?>
                            <div class="description">
                                <?php echo trim( $description ); ?>
                            </div>
                        <?php } ?>

                        <?php if ( !empty($btn_text) ) { ?>
                            <span class="btn  btn-theme btn-shop"><?php echo trim($btn_text); ?></span>
                        <?php } ?>
                    </div>
                </div>

            <?php if ( !empty($link) ) { ?>
                </a>
            <?php } ?>
        </div>
        <?php
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Banner_Shop );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Banner_Shop );
}