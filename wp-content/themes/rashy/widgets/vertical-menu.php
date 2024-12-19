<?php
extract( $args );
extract( $instance );

if ( $title ) {
    echo trim($before_title)  .'<i class="fa fa-bars" ></i>'. trim( $title ) . $after_title;
}
$nav_menu = ( $nav_menu !='' ) ? wp_get_nav_menu_object( $nav_menu ) : false;
if ( $nav_menu ) {
	$position_class = ($position=='left') ? 'menu-left' : 'menu-right';
	$args = array(
	    'menu' => $nav_menu,
	    'container_class' => 'collapse navbar-collapse navbar-ex1-collapse goal-vertical-menu '.$position_class,
	    'menu_class' => 'nav navbar-nav navbar-vertical-mega',
	    'fallback_cb' => '',
	    'walker' => new Rashy_Nav_Menu()
	);
	?>
	<aside class="widget-vertical-menu">
	    <?php wp_nav_menu($args); ?>
	</aside>
<?php } ?>