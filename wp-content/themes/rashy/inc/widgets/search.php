<?php

class Rashy_Search extends Goal_Widget {
    public function __construct() {
        parent::__construct(
            'goal_search',
            esc_html__('Goal Search Widget', 'rashy'),
            array( 'description' => esc_html__( 'Show search form in sidebar', 'rashy' ), )
        );
        $this->widgetName = 'search';
    }

    public function getTemplate() {
        $this->template = 'search.php';
    }

    public function widget( $args, $instance ) {
        $this->display($args, $instance);
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => 'Search',
            'post_type' => ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'rashy' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>">
                <?php echo esc_html__('Type:', 'rashy' ); ?>
            </label>
            <br>
            <select id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo esc_attr($this->get_field_name('post_type')); ?>">
                <?php foreach (get_post_types(array('public' => true)) as $key => $value) { ?>
                    <?php if($key!='attachment' && $key!='goal_testimonial' && $key!='goal_brand' && $key!='goal_footer' && $key!='goal_megamenu'){ ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected($instance['post_type'],$key); ?> ><?php echo esc_html( $value ); ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
        return $instance;
    }
}
if ( function_exists('goal_framework_reg_widget') ) {
    goal_framework_reg_widget('Rashy_Search');
}