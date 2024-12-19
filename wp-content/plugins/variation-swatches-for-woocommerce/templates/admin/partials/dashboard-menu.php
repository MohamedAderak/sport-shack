<?php
$addon_page_slug = TA_WC_Variation_Swatches::is_woo_core_active() ? 'variation-swatches-addons' : 'woosuite-core-addons';
?>
<div class="opt-desh-menu-wrap">
    <ul class="opt-desh-menu">
        <li class="active">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page='.$addon_page_slug ) ); ?>"
               class="<?php echo isset( $_GET['page'] ) && $_GET['page'] == $addon_page_slug ? 'active-desh-menu' : ''; ?>">
				<?php _e( 'Addons', 'wcvs' ); ?>
            </a>
        </li>
        <li>
            <a target="_blank" href="https://aovup.com/docs?utm_source=user-dashboard&utm_medium=header" target="_blank">
				<?php _e( 'Docs', 'wcvs' ); ?>
            </a>
        </li>
        <li>
            <a target="_blank" href="https://aovup.com/support?utm_source=user-dashboard&utm_medium=header" target="_blank">
				<?php _e( 'Support', 'wcvs' ); ?>
            </a>
        </li>
        <li>
            <a target="_blank" href="https://aovup.com/my-account?utm_source=user-dashboard&utm_medium=header" target="_blank">
				<?php _e( 'My Account ', 'wcvs' ); ?>
            </a>
        </li>
    </ul>
</div>