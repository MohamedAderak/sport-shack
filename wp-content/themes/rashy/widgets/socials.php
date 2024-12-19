<?php 
$title = apply_filters('widget_title', $instance['title']);
if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}
?>
<ul class="social-top">
    <?php foreach( $socials as $key=>$social):
            if( isset($social['status']) && !empty($social['page_url']) ): ?>
                <li>
                    <a href="<?php echo esc_url($social['page_url']);?>" class="<?php echo esc_attr($key); ?>" target="_blank">
                        <i class="fab fa-<?php echo esc_attr($key); ?> bo-social-<?php echo esc_attr($key); ?>">&nbsp;</i><span class="hidden"><?php echo trim($social['name']); ?></span>
                    </a>
                </li>
    <?php
            endif;
        endforeach;
    ?>
</ul>