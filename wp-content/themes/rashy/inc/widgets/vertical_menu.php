<?php

class Rashy_Vertical_Menu extends Goal_Widget {
    public function __construct() {
        parent::__construct(
            'goal_vertical_menu',
            esc_html__('Goal Vertical Menu Widget', 'rashy'),
            array( 'description' => esc_html__( 'Show Vertical Menu', 'rashy' ), )
        );
        $this->widgetName = 'vertical_menu';
    }

    public function getTemplate() {
        $this->template = 'vertical-menu.php';
    }

    public function widget( $args, $instance ) {
        $this->display($args, $instance);
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => 'Custom Menu',
            'nav_menu' => '',
            'position' => 'left',
            'menu_show' => 'on'
        );
        $instance = wp_parse_args((array) $instance, $defaults);

        // Widget admin form        
        $menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
        foreach ($menus as $menu) {
            $option_menu[$menu->slug] = $menu->name;
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'rashy' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title']  ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'nav_menu' )); ?>">Menu:</label>
            <br>
            <select name="<?php echo esc_attr($this->get_field_name( 'nav_menu' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'nav_menu' )); ?>">
                <option value="" <?php selected( $instance['nav_menu'], $menu->slug ); ?>> <?php esc_html_e('--- Select Menu ---', 'rashy'); ?></option>
                <?php foreach ($menus as $key => $menu): ?>
                    <option value="<?php echo esc_attr( $menu->slug ); ?>" <?php selected( $instance['nav_menu'], $menu->slug ); ?>><?php echo esc_html( $menu->name ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'position' )); ?>">Position:</label>
            <br>
            <select name="<?php echo esc_attr($this->get_field_name( 'position' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'position' )); ?>">
                <option value="left" <?php selected( $instance['position'], 'left' ); ?>><?php esc_html_e( 'Left', 'rashy' ); ?></option>
                <option value="right" <?php selected( $instance['position'], 'right' ); ?>><?php esc_html_e( 'Right', 'rashy' ); ?></option>
            </select>
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['nav_menu'] = $new_instance['nav_menu'];
        $instance['position'] = $new_instance['position'];
        return $instance;
    }
}
if ( function_exists('goal_framework_reg_widget') ) {
    goal_framework_reg_widget('Rashy_Vertical_Menu');
}