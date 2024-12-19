<?php
/**
 * functions preset for goal framework
 *
 * @package    goal-framework
 * @author     Team Goalthemes <goaltheme@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Goal Framework
 */


function goal_framework_init_redux() {

    add_action( 'goal_framework_preset', 'goal_framework_redux_preset' );
    add_action( 'admin_enqueue_scripts', 'goal_framework_redux_scripts' );

    add_action( 'wp_ajax_goal_framework_new_preset', 'goal_framework_redux_save_new_preset' );
    add_action( 'wp_ajax_nopriv_goal_framework_new_preset', 'goal_framework_redux_save_new_preset' );

    add_action( 'wp_ajax_goal_framework_set_default_preset', 'goal_framework_redux_set_default_preset' );
    add_action( 'wp_ajax_nopriv_goal_framework_set_default_preset', 'goal_framework_redux_set_default_preset' );

    add_action( 'wp_ajax_goal_framework_delete_preset', 'goal_framework_redux_delete_preset' );
    add_action( 'wp_ajax_nopriv_goal_framework_delete_preset', 'goal_framework_redux_delete_preset' );
    
    add_action( 'wp_ajax_goal_framework_duplicate_preset', 'goal_framework_redux_duplicate_preset' );
    add_action( 'wp_ajax_nopriv_goal_framework_duplicate_preset', 'goal_framework_redux_duplicate_preset' );
}

function goal_framework_redux_scripts() {
    wp_enqueue_script( 'goal-framework-admin', GOAL_FRAMEWORK_URL . 'assets/admin.js', array( 'jquery'  ), '20131022', true );
    wp_enqueue_style( 'goal-framework-admin', GOAL_FRAMEWORK_URL . 'assets/backend.css' );
}

function goal_framework_redux_duplicate_preset() {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $preset = isset($_POST['default_preset']) ? $_POST['default_preset'] : '';
    $opt_name = apply_filters( 'goal_framework_get_opt_name', '' );
    $preset_option = get_option( $opt_name.$preset );
    
    $key = strtotime('now');
    if ( !empty($title) ) {
        $presets = get_option( 'goal_framework_presets' );
        $key = strtotime('now');
        $presets[$key] = $title;
        update_option( 'goal_framework_presets', $presets );
        update_option( $opt_name.$key, $preset_option );
        update_option( 'goal_framework_preset_default', $key );
    }
}

function goal_framework_redux_delete_preset() {
    $preset = isset($_POST['default_preset']) ? $_POST['default_preset'] : '';
    $default_preset = get_option( 'goal_framework_preset_default' );

    if ( !empty($preset) ) {
        $presets = get_option( 'goal_framework_presets' );
        if ( isset($presets[$preset]) ) {
            unset($presets[$preset]);
        }
        update_option( 'goal_framework_presets', $presets );
        if ($preset == $default_preset) {
            update_option( 'goal_framework_preset_default', '' );
        }
    }
}

function goal_framework_redux_set_default_preset() {
    $default_preset = isset($_POST['default_preset']) ? $_POST['default_preset'] : '';
    update_option( 'goal_framework_preset_default', $default_preset );
    die();
}

function goal_framework_redux_save_new_preset() {
    $new_preset = isset($_POST['new_preset']) ? $_POST['new_preset'] : '';

    if ( !empty($new_preset) ) {
        $presets = get_option( 'goal_framework_presets' );
        $key = strtotime('now');
        $presets[$key] = $new_preset;
        update_option( 'goal_framework_presets', $presets );
        update_option( 'goal_framework_preset_default', $key );
    }
    die();
}

function goal_framework_redux_preset() {
    // preset
    $presets = get_option( 'goal_framework_presets' );

    $default_preset = get_option( 'goal_framework_preset_default' );
    if ( empty($presets) || !is_array($presets) ) {
        $presets = array();
    }
    ?>
    <section class="preset-section">
        <h3><?php esc_html_e( 'Preset Manager', 'goal-framework' ); ?></h3>
        
        <div class="preset-content">
            <p class="note"><?php esc_html_e( 'Current preset default: ', 'goal-framework' ); ?> <strong><?php echo (isset($presets[$default_preset]) ? $presets[$default_preset] : 'Default'); ?></strong></p>

            <label><?php esc_html_e( 'Create a new preset', 'goal-framework' ); ?></label>
            <div><input type="text" name="new_preset" class="new_preset"> <button type="button" name="submit_new_preset" class="button submit-new-preset"><?php esc_html_e( 'Add new', 'goal-framework' ); ?></button></div>
        
            
            <div class="set_default">
                <label><?php esc_html_e( 'Set default preset', 'goal-framework' ); ?></label>
                <br>
                <select class="set_default_preset" name="default_preset">
                    <option value=""><?php esc_html_e( 'Default', 'goal-framework' ); ?></option>
                    <?php foreach ($presets as $key => $preset) { ?>
                        <option value="<?php echo $key; ?>"<?php echo $key == $default_preset ? 'selected="selected"' : ''; ?>><?php echo $preset; ?></option>
                    <?php } ?>
                </select>
                <button type="button" name="submit_preset" class="button submit-preset"><?php esc_html_e( 'Set Default', 'goal-framework' ); ?></button>
                <button type="button" name="submit_duplicate_preset" class="button submit-duplicate-preset"><?php esc_html_e( 'Duplicate', 'goal-framework' ); ?></button>
                <button type="button" name="submit_delete_preset" class="button submit-delete-preset"><?php esc_html_e( 'Delete Preset', 'goal-framework' ); ?></button>
                <div class="preset_des"><?php esc_html_e( 'Key:', 'goal-framework' ); ?> <span class="key"><?php echo $default_preset; ?></span></div>
            </div>
            
        </div>
        <br>
        <br>
    </section>
    <?php
}