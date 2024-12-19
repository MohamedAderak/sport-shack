<?php
/**
 * Wishlist header
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $wishlist \YITH_WCWL_Wishlist Current wishlist
 * @var $is_custom_list bool Whether current wishlist is custom
 * @var $can_user_edit_title bool Whether current user can edit title
 * @var $form_action string Action for the wishlist form
 * @var $page_title string Page title
 * @var $fragment_options array Array of items to use for fragment generation
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist ); ?>

<form id="yith-wcwl-form" action="<?php echo esc_attr( $form_action ); ?>" method="post" class="woocommerce yith-wcwl-form wishlist-fragment" data-fragment-options="<?php echo esc_attr( json_encode( $fragment_options ) ); ?>">

	<!-- TITLE -->
	<?php
	do_action( 'yith_wcwl_before_wishlist_title', $wishlist );

	if ( ! empty( $page_title ) ) :
		?>
		<div class="wishlist-title-container">
			<div class="wishlist-title <?php echo esc_attr( $can_user_edit_title ? 'wishlist-title-with-form' : ''); ?>">
				<?php echo apply_filters( 'yith_wcwl_wishlist_title', '<h2>' . esc_html( $page_title ) . '</h2>' ); ?>
				<?php if ( $can_user_edit_title ) : ?>
					<a class="btn button show-title-form">
						<?php echo apply_filters( 'yith_wcwl_edit_title_icon', '<i class="fas fa-pencil-alt"></i>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'Edit title', 'rashy' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<?php if ( $can_user_edit_title ) : ?>
				<div class="hidden-title-form">
					<div class="flex-middle-sm">
						<input type="text" value="<?php echo esc_attr( $page_title ); ?>" name="wishlist_name"/>
						<div class="edit-title-buttons">
							<a role="button" href="#" class="hide-title-form btn btn-danger">
								<?php echo apply_filters( 'yith_wcwl_cancel_wishlist_title_icon', '<i class="fas fa-times"></i>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<a role="button" href="#" class="save-title-form btn btn-success">
								<?php echo apply_filters( 'yith_wcwl_save_wishlist_title_icon', '<i class="fas fa-check"></i>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	endif;

	do_action( 'yith_wcwl_before_wishlist', $wishlist );
	?>
