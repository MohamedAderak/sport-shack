<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
if ( ! empty( $tabs ) ) :
	$layout = rashy_product_get_layout_type();
	if ( $layout == 'v7' ) {
	?>
		<div class="panel-group goal-wc-tabs" id="woocommerce-accordion" role="tablist" aria-multiselectable="true">
			<?php $i = 0; ?>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="panel panel-default">
				    <div id="heading-<?php echo esc_attr( $key ); ?>" class="panel-heading <?php echo esc_attr( $key ); ?>_tab" role="tab">
				      	<h4 class="panel-title">
					        <a class="<?php echo esc_attr($i !== 0 ? 'collapsed' : ''); ?>" role="button" data-toggle="collapse" data-parent="#woocommerce-accordion" href="#collapse-<?php echo esc_attr( $key ); ?>" aria-expanded="true" aria-controls="collapse-<?php echo esc_attr( $key ); ?>">
					          	<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
					        </a>
				      	</h4>
				    </div>
				    <div id="collapse-<?php echo esc_attr( $key ); ?>" class="panel-collapse collapse <?php echo esc_attr($i == 0 ? 'in' : ''); ?>" role="tabpanel" aria-labelledby="heading-<?php echo esc_attr( $key ); ?>">
				      	<div class="panel-body">
				      		<?php
							if ( isset( $tab['callback'] ) ) {
								call_user_func( $tab['callback'], $key, $tab );
							}
							?>
				      	</div>
				    </div>
			  	</div>
		  	<?php $i++; endforeach; ?>
		</div>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	<?php } elseif ( $layout == 'v3' || $layout == 'v4' || $layout == 'v6' ) { ?>
		<div class="woocommerce-tabs tabs-v2 goal-wc-tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="tab-item <?php echo esc_attr( $key ); ?>_tab">
					<a href="javascript:void(0);" class="tab-header-title">
						<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
					</a>
					<?php
					if ( isset( $tab['callback'] ) ) {
						?>
						<div class="tabs-content-wrapper">
							<?php call_user_func( $tab['callback'], $key, $tab ); ?>
							<span class="close-tab"><i class="ti-close"></i></span>
						</div>
						<?php
					}
					?>
				</div>
			<?php endforeach; ?>
			<div class="overlay-tabs"></div>
		</div>
	<?php } elseif ( $layout == 'v8' ) { ?>
		<div class="tabs-v3 goal-wc-tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="tab-item <?php echo esc_attr( $key ); ?>_tab">
					<div class="head-tab">
						<span><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></span>
					</div>
					<?php
					if ( isset( $tab['callback'] ) ) {
						?>
						<div class="content-wrapper">
							<?php call_user_func( $tab['callback'], $key, $tab ); ?>
						</div>
						<?php
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php } else { ?>
		<div class="woocommerce-tabs tabs-v1 goal-wc-tabs">
			<div class="tab-top">
				<ul class="tabs-list nav nav-tabs">
					<?php $i = 0; ?>
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr($i == 0 ? 'active' : '');?>">
							<a data-toggle="tab" href="#tabs-list-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
						</li>
					<?php $i++; endforeach; ?>
				</ul>
			</div>
			<div class="tab-content">
			<?php $i = 0; ?>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="tab-pane<?php echo esc_attr($i == 0 ? ' active in' : ''); ?>" id="tabs-list-<?php echo esc_attr( $key ); ?>">
					<?php
					if ( isset( $tab['callback'] ) ) {
						call_user_func( $tab['callback'], $key, $tab );
					}
					?>
				</div>
			<?php $i++; endforeach; ?>
			</div>

			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
		</div>
	<?php } ?>
<?php endif; ?>