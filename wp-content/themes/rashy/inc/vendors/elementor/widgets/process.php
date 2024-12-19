<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Process extends Widget_Base {

	public function get_name() {
        return 'rashy_process';
    }

	public function get_title() {
        return esc_html__( 'Goal Process', 'rashy' );
    }

	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_title', [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'des', [
                'label' => esc_html__( 'Description', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Icon Image', 'rashy' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Icon Image', 'rashy' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your proces link here', 'rashy' ),
            ]
        );

        $this->add_control(
            'process',
            [
                'label' => esc_html__( 'Process', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'placeholder' => esc_html__( 'Enter your Process here', 'rashy' ),
                'fields' => $repeater->get_controls(),
            ]
        );
        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your column number here', 'rashy' ),
                'default' => 4
            ]
        );
        $this->add_control(
            'img_line_src',
            [
                'name' => 'image_line',
                'label' => esc_html__( 'Background Image Space Columns', 'rashy' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Background Image', 'rashy' ),
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
                'selectors' => [
                    '{{WRAPPER}} .proces-item' => 'text-align: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .widget-process .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'rashy' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .widget-process .title',
            ]
        );

        $this->add_control(
            'des',
            [
                'label' => esc_html__( 'Description Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .des' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'rashy' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .des',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($process) ) {
            if ( empty($columns) ) {
                $columns = 4;
            }
            $bcol = 12/$columns;

            ?>
            <?php 
                $img_line_src = ( isset( $img_line_src['id'] ) && $img_line_src['id'] != 0 ) ? wp_get_attachment_url( $img_line_src['id'] ) : '';
                $style_bg = '';
                if ( !empty($img_line_src) ) {
                    $style_bg = ' style="background:url('.esc_url($img_line_src).') no-repeat"';
                }
            ?>
            <div class="widget-process <?php echo esc_attr($el_class); ?>">
                <div class="row">
                    <?php foreach ($process as $proces) { ?>
                        <?php
                            $img_src = ( isset( $proces['img_src']['id'] ) && $proces['img_src']['id'] != 0 ) ? wp_get_attachment_url( $proces['img_src']['id'] ) : '';
                            if ( $img_src ) {
                        ?>
                                <div class="proces-item updow col-sm-6 col-md-<?php echo esc_attr($bcol); ?>">
                                    <?php echo '<div class="line-space"'.$style_bg.'></div>'; ?>
                                    <div class="top-img">
                                        <?php if ( !empty($proces['link']) ) { ?>
                                            <a href="<?php echo esc_url($proces['link']); ?>" <?php echo (!empty($proces['title']) ? 'title="'.esc_attr($proces['title']).'"' : ''); ?>>
                                        <?php } ?>
                                            <img src="<?php echo esc_url($img_src); ?>" <?php echo (!empty($proces['title']) ? 'alt="'.esc_attr($proces['title']).'"' : 'alt=""'); ?>>
                                        <?php if ( !empty($proces['link']) ) { ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <?php if ( !empty($proces['list_title']) ) { ?>
                                        <h2 class="title"><?php echo wp_kses_post($proces['list_title']); ?></h2>
                                    <?php } ?>
                                    <?php if ( !empty($proces['des']) ) { ?>
                                        <div class="des"><?php echo wp_kses_post($proces['des']); ?></div>
                                    <?php } ?>
                                </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }

}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Process );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Process );
}