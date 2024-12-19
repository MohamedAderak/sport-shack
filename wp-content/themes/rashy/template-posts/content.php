<?php
/**
 *
 * The default template for displaying content
 * @since 1.0
 * @version 1.2.0
 *
 */

if ( !is_single() ) {
	get_template_part( 'template-posts/loop/inner-grid' );
} else {
	get_template_part( 'template-posts/single/inner' );
}