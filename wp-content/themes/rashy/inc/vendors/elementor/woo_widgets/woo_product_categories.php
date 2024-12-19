<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Categories extends Widget_Base {

    public function get_name() {
        return 'rashy_woo_categories';
    }

    public function get_title() {
        return esc_html__( 'Goal Product Categories', 'rashy' );
    }

    public function get_icon() {
        return 'ti-bag';
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

        $repeater = new Repeater();

        $repeater->add_control(
            'image_icon',
            [
                'label' => esc_html__( 'Image or Icon', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'icon' => esc_html__('Icon', 'rashy'),
                    'image' => esc_html__('Image', 'rashy'),
                ),
                'default' => 'image',
              
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'rashy' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-star',
                'condition' => [
                    'image_icon' => 'icon',
                ],
            ]
        );

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
                'condition' => [
                    'image_icon' => 'image',
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
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'This is the heading', 'rashy' ),
                'placeholder' => esc_html__( 'Enter your title', 'rashy' ),
            ]
        );

        $repeater->add_control(
            'slug', [
                'label' => esc_html__( 'Category Slug', 'rashy' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( '//your-link.com', 'rashy' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => esc_html__( 'Categories', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
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
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'carousel' => esc_html__('Carousel', 'rashy'),
                    'grid' => esc_html__('Grid', 'rashy'),
                    'special' => esc_html__('Special', 'rashy'),
                ),
                'default' => 'carousel',
            ]
        );

        $columns = range( 1, 12 );
        $columns = array_combine( $columns, $columns );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => $columns,
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'rashy' ),
                'options' => $columns,
                'condition' => [
                    'columns!' => '1',
                    'layout_type' => 'carousel',
                ],
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your rows number here', 'rashy' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label' => esc_html__( 'Show Nav', 'rashy' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'pagination_style',
            [
                'label' => esc_html__( 'Pagination', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Normal', 'rashy'),
                    'special' => esc_html__('Special', 'rashy'),
                ),
                'default' => '',
                'condition' => [
                    'layout_type' => 'carousel',
                ]
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'rashy' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'rashy' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'rashy' ),
                'label_off'     => esc_html__( 'No', 'rashy' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'rashy' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'rashy' ),
                'label_off'     => esc_html__( 'No', 'rashy' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
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
                'selector' => '{{WRAPPER}} .widget-categories-box.style2 .categories-item .categories-inner',
            ]
        );

        $this->add_responsive_control(
            'padding-box',
            [
                'label' => esc_html__( 'Padding', 'rashy' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .widget-categories-box.style2 .categories-item .categories-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rashy' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .widget-categories-box.style2 .categories-item .categories-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .categories-item .cate-image a i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .categories-inner:hover  .categories-item .cate-image a i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Icon Typography', 'rashy' ),
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .categories-item .cate-image a i',
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-categories-box .categories-item .cat-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .categories-item .cate-image::before' => 'background: linear-gradient(to bottom, transparent 0%, {{VALUE}} 100%);',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-categories-box .categories-item .cat-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget-categories-box .categories-item .categories-inner:hover .cat-title' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'rashy' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .widget-categories-box .categories-item .cat-title',
            ]
        );

        $this->add_control(
            'nb_color',
            [
                'label' => esc_html__( 'Number item Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-nb' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_nb_color',
            [
                'label' => esc_html__( 'Background Number item Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .categories-item .cate-content.icon .product-nb' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hv_nb_color',
            [
                'label' => esc_html__( 'Number Hover item Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .categories-item .categories-inner:hover .cate-content.icon .product-nb' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_hv_nb_color',
            [
                'label' => esc_html__( 'Background Hover Number item Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .categories-item .categories-inner:hover .cate-content.icon .product-nb' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Number item Typography', 'rashy' ),
                'name' => 'nb_typography',
                'selector' => '{{WRAPPER}} .product-nb',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($categories) ) {
            $rows = isset($rows) ? $rows : 1;
            $columns = !empty($columns) ? $columns : 5;
            $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 4;
            $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 2;
            
            $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
            $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
            $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;
        ?>
            <div class="widget-categories-box <?php echo esc_attr($style); ?> <?php echo esc_attr($pagination_style.' '.$el_class); ?>">
                <?php if ( $layout_type == 'carousel' ): ?>
                    
                    <div class="slick-carousel <?php echo esc_attr( ( $columns >= count($categories) )?'hidden-dot':'' ); ?>"
                        data-rows="<?php echo esc_attr( $rows ); ?>"
                        data-items="<?php echo esc_attr($columns); ?>"
                        data-smallmedium="<?php echo esc_attr( $columns_tablet ); ?>"
                        data-extrasmall="<?php echo esc_attr($columns_mobile); ?>"

                        data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                        data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                        data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                        data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>">

                        <?php foreach ($categories as $item):
                            ?>
                            <div class="categories-item">
                            <div class="categories-inner">
                                <?php
                                $html = $link = '';

                                $term = get_term_by('slug', $item['slug'], 'product_cat');
                                if ( $term ) {
                                    $link = get_term_link($term);
                                }
                                if ( ! empty( $item['link'] ) ) {
                                    $link = $item['link'];
                                }
                                if ( $item['image_icon'] == 'image' ) {
                                    if ( ! empty( $item['image']['url'] ) ) {
                                        $this->add_render_attribute( 'image', 'src', $item['image']['url'] );
                                        $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $item['image'] ) );
                                        $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $item['image'] ) );

                                        $image_html = Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' );

                                        if ( ! empty( $link ) ) {
                                            $image_html = '<a href="'.esc_url($link).'">' . $image_html . '</a>';
                                        }

                                        $html .= '<div class="cate-image img">' . $image_html . '</div>';

                                         if ( $item['title'] ) {
                                            $html .= '<div class="cate-content image">';

                                            $title_html = $item['title'];

                                            if ( ! empty( $link ) ) {
                                                $html .= '<a href="'.esc_url($link).'"><h3 class="cat-title">'.$title_html.'</h3></a>';
                                            } else {
                                                $html .= sprintf( '<h3 class="cat-title">%1$s</h3>', $title_html );
                                            }

                                            $html .= '<div class="product-nb">'.sprintf(_n('%d Product', '%d Products', $term->count, 'rashy'), $term->count).'</div>';

                                            $html .= '</div>';
                                        }
                                    }
                                } elseif ( $item['image_icon'] == 'icon' && !empty($item['icon'])) {

                                    $icon_html = '<i class="'.$item['icon'].'"></i>';

                                    if ( ! empty( $link ) ) {
                                        $icon_html = '<a href="'.esc_url($link).'">' . $icon_html . '</a>';
                                    }

                                    $html .= '<div class="cate-image icon">'.$icon_html.'</div>';

                                    if ( $item['title'] ) {
                                        $html .= '<div class="cate-content icon">';

                                        $title_html = $item['title'];

                                        if ( ! empty( $link ) ) {
                                            $html .= '<a href="'.esc_url($link).'"><h3 class="cat-title">'.$title_html.'</h3></a>';
                                        } else {
                                            $html .= sprintf( '<h3 class="cat-title">%1$s</h3>', $title_html );
                                        }

                                        $html .= '<div class="product-nb">'.sprintf(_n('%d Product', '%d Products', $term->count, 'rashy'), $term->count).'</div>';

                                        $html .= '</div>';
                                    }
                                }
                                echo trim($html);
                                ?>
                            </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                
                
                    
               <?php elseif( $layout_type == 'grid' ): ?>
                    <?php
                        $mdcol = 12/$columns;
                        $smcol = 12/$columns_tablet;
                        $xscol = 12/$columns_mobile;
                    ?>  
                    <div class="row">
                        <?php foreach ($categories as $item): ?>
                            <div class="col-md-<?php echo esc_attr($mdcol); ?> col-sm-<?php echo esc_attr($smcol); ?> col-xs-<?php echo esc_attr($xscol); ?>">
                                <div class="categories-item">
                                    <div class="categories-inner <?php echo esc_attr($style); ?>">
                                        <?php
                                        $html = $link = '';

                                        $term = get_term_by('slug', $item['slug'], 'product_cat');
                                        if ( $term ) {
                                            $link = get_term_link($term);
                                        }
                                        if ( ! empty( $item['link'] ) ) {
                                            $link = $item['link'];
                                        }
                                        if ( $item['image_icon'] == 'image' ) {
                                            if ( ! empty( $item['image']['url'] ) ) {
                                                $this->add_render_attribute( 'image', 'src', $item['image']['url'] );
                                                $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $item['image'] ) );
                                                $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $item['image'] ) );


                                                $image_html = Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' );

                                                if ( ! empty( $link ) ) {
                                                    $image_html = '<a href="'.esc_url($link).'">' . $image_html . '</a>';
                                                }

                                                $html .= '<div class="cate-image img">' . $image_html . '</div>';
                                            }
                                        } elseif ( $item['image_icon'] == 'icon' && !empty($item['icon'])) {
                                            $html .= '<div class="cate-image icon"><i class="'.$item['icon'].'"></i></div>';
                                        }

                                        if ( $item['title'] ) {
                                            $html .= '<div class="cate-content">';
                                                $html .= '<div class="cate-content-inner">';
                                                $title_html = $item['title'];

                                                if ( ! empty( $link ) ) {
                                                    $html .= '<a href="'.esc_url($link).'"><h3 class="cat-title">'.$title_html.'</h3></a>';
                                                } else {
                                                    $html .= sprintf( '<h3 class="cat-title">%1$s</h3>', $title_html );
                                                }
                                                
                                                $html .= '<div class="product-nb">'.sprintf(_n('%d Product', '%d Products', $term->count, 'rashy'), $term->count).'</div>';
                                                
                                                $html .= '</div>';
                                            $html .= '</div>';
                                        }

                                        echo trim($html);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                
                    <div class="layout-special">
                         <?php foreach ($categories as $item): ?>
                           
                                <div class="categories-item">
                                    <div class="categories-inner">
                                        <?php
                                        $html = $link = '';

                                        $term = get_term_by('slug', $item['slug'], 'product_cat');
                                        if ( $term ) {
                                            $link = get_term_link($term);
                                        }
                                        if ( ! empty( $item['link'] ) ) {
                                            $link = $item['link'];
                                        }
                                        if ( $item['image_icon'] == 'image' ) {
                                            if ( ! empty( $item['image']['url'] ) ) {
                                                $this->add_render_attribute( 'image', 'src', $item['image']['url'] );
                                                $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $item['image'] ) );
                                                $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $item['image'] ) );


                                                $image_html = Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' );

                                                if ( ! empty( $link ) ) {
                                                    $image_html = '<a href="'.esc_url($link).'">' . $image_html . '</a>';
                                                }

                                                $html .= '<div class="cate-image img">' . $image_html . '</div>';
                                            }
                                        } elseif ( $item['image_icon'] == 'icon' && !empty($item['icon'])) {
                                            $html .= '<div class="cate-image icon"><i class="'.$item['icon'].'"></i></div>';
                                        }

                                        if ( $item['title'] ) {
                                            $html .= '<div class="cate-content">';

                                            $title_html = $item['title'];

                                            if ( ! empty( $link ) ) {
                                                $html .= '<a href="'.esc_url($link).'"><h3 class="cat-title">'.$title_html.'</h3></a>';
                                            } else {
                                                $html .= sprintf( '<h3 class="cat-title">%1$s</h3>', $title_html );
                                            }
                                            
                                            $html .= '<div class="product-nb">'.sprintf(_n('%d Product', '%d Products', $term->count, 'rashy'), $term->count).'</div>';
                                            
                                            $html .= '</div>';
                                        }

                                        echo trim($html);
                                        ?>

                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </div> 
                <?php endif; ?>
                    
            </div>
            <?php
        }
    }

}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Categories );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Categories );
}