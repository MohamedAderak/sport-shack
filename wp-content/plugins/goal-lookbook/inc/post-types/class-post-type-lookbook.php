<?php
/**
 * Image Lookbook post type
 *
 * @package    goal-lookbook
 * @author     GoalThemes <goalthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 GoalThemes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class GoalLookbook_PostType_Lookbook{

	/**
	 * init action and filter data to define resource post type
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_display_settings' ) );
		add_action( 'save_post', array( __CLASS__, 'save_custom_meta_box' ), 10, 3  );
		// custom columns
		add_filter( 'manage_lookbook_posts_columns', array( __CLASS__, 'custom_columns' ), 100 );
		add_filter( 'manage_lookbook_posts_custom_column', array( __CLASS__, 'custom_column' ), 10, 2 );
		// script
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'scripts' ) );
		add_action( 'wp_ajax_goal_lookbook_search_product', array( __CLASS__, 'search_product' ) );
        add_action( 'wp_ajax_nopriv_goal_lookbook_search_product', array( __CLASS__, 'search_product' ) );
	}
	/**
	 *
	 */
	public static function definition() {
		$labels = array(
			'name'                  => __( 'Goal Lookbooks', 'goal-lookbook' ),
			'singular_name'         => __( 'Lookbook', 'goal-lookbook' ),
			'add_new'               => __( 'Add New Lookbook', 'goal-lookbook' ),
			'add_new_item'          => __( 'Add New Lookbook', 'goal-lookbook' ),
			'edit_item'             => __( 'Edit Lookbook', 'goal-lookbook' ),
			'new_item'              => __( 'New Lookbook', 'goal-lookbook' ),
			'all_items'             => __( 'All Lookbooks', 'goal-lookbook' ),
			'view_item'             => __( 'View Lookbook', 'goal-lookbook' ),
			'search_items'          => __( 'Search Lookbook', 'goal-lookbook' ),
			'not_found'             => __( 'No Lookbooks found', 'goal-lookbook' ),
			'not_found_in_trash'    => __( 'No Lookbooks found in Trash', 'goal-lookbook' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Goal Lookbooks', 'goal-lookbook' ),
		);

		$labels = apply_filters( 'goallookbook_postype_lookbook_labels' , $labels );

		register_post_type( 'lookbook',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title' ),
				'public'            => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'has_archive'        => false,
				'rewrite'           => array( 'slug' => __( 'lookbook', 'goal-lookbook' ) ),
				'menu_position'     => 51,
				'show_in_menu'  	=> true,
			)
		);
	}
	
	public static function scripts() {
		wp_enqueue_media();
		wp_enqueue_style( 'lookbook-admin-style', GOALLOOKBOOK_PLUGIN_URL . 'assets/admin-style.css' );
		wp_enqueue_script( 'lookbook-upload', GOALLOOKBOOK_PLUGIN_URL . 'assets/upload.js', array('jquery', 'wp-pointer'), '20141010', true );
		wp_register_script( 'lookbook-scene-cropping', GOALLOOKBOOK_PLUGIN_URL . 'assets/scene-cropping.js', array('jquery', 'jquery-ui-autocomplete'), '20141010', true );
		wp_localize_script( 'lookbook-scene-cropping', 'goal_lookbook_vars', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'edit_img' => GOALLOOKBOOK_PLUGIN_URL . 'assets/images/edit.gif',
			'delete_img' => GOALLOOKBOOK_PLUGIN_URL . 'assets/images/delete.gif'
		));
		wp_enqueue_script( 'lookbook-scene-cropping' );
	}

	public static function custom_markup($object) {
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	    	$zones = get_post_meta($object->ID, 'goal_lookbook_zones', true);
	    ?>
	    	<script type="text/javascript">
	    		var goalStartingData = new Array();
	    		<?php
    			if (!empty($zones)):
    				$i = 0;
    				foreach ($zones as $key => $value):
						$args = array(
						  	'name' => $value['slug'],
						  	'post_type' => $value['post_type'],
						  	'post_status' => 'publish',
						  	'posts_per_page' => 1
						);
						$posts = get_posts($args);
    					?>
    					goalStartingData[<?php echo esc_js($i); ?>] = new Array(
    						'<?php echo esc_js(!empty($posts) && isset($posts[0]) ? $posts[0]->post_title : ''); ?>',
    						'<?php echo esc_js($value['slug']); ?>',
    						<?php echo isset($value['x1']) ? esc_js($value['x1']) : 0; ?>,
    						<?php echo isset($value['y1']) ? esc_js($value['y1']) : 0; ?>,
    						<?php echo isset($value['width']) ? esc_js($value['width']) : 0; ?>,
    						<?php echo isset($value['height']) ? esc_js($value['height']) : 0; ?>,
    						'<?php echo isset($value['post_type']) ? esc_js($value['post_type']) : 'post'; ?>'
    					);
    			<?php
					$i++; endforeach;
				endif; ?>
	    	</script>
	        <div class="goal-image-settings">
	        	
	            <label for="image"><?php _e( 'Choose a image', 'goal-lookbook' ); ?></label>
	            <br>
	            <br>
	            <input class="upload_image" name="goal-image" type="hidden" value="<?php echo get_post_meta($object->ID, 'goal_lookbook_image', true); ?>" />
		        <div class="upload_image_action">
		            <input type="button" class="button add-image" value="Add">
		            <input type="button" class="button remove-image" value="Remove">
		        </div>
		        <br>
		        <div id="large_scene_image">
		        	<div class="screenshot"></div>
		        </div>
		        <div id="ajax_choose_product">
		        	<input id="product_autocomplete_input" name="product_name" type="text" value="" placeholder="<?php esc_html_e('Search for a product&hellip;', 'goal-lookbook'); ?>" />
		        </div>
	        </div>
	    <?php
	}

	public static function save_custom_meta_box($post_id, $post, $update)
	{
	    if (!isset($_POST['meta-box-nonce']) || !wp_verify_nonce($_POST['meta-box-nonce'], basename(__FILE__)))
	        return $post_id;

	    if (!current_user_can('edit_post', $post_id))
	        return $post_id;

	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	        return $post_id;

	    $slug = 'lookbook';
	    if ($slug != $post->post_type)
	        return $post_id;

	    if (isset($_POST['zones'])) {
	        $zones = $_POST['zones'];
	        update_post_meta($post_id, 'goal_lookbook_zones', $zones);
	    }
	    if ( !empty($_POST['goal-image']) ) {
	    	update_post_meta($post_id, 'goal_lookbook_image', $_POST['goal-image']);
	    }
	}

	public static function add_display_settings() {
	    add_meta_box("lookbook-setting", __( 'Settings', 'goal-lookbook' ), array( __CLASS__, 'custom_markup' ), "lookbook", "normal", "high", null);
	}

	public function search_product() {
		// Query for suggestions
        $args = array(
        	's' => $_REQUEST['term'],
        	'post_type' => 'product',
        );
        $posts = get_posts( $args );
        $suggestions = array();

        foreach ($posts as $post):
            $suggestion = array();
            $suggestion['label'] = esc_html($post->post_title);
            $suggestion['slug'] = esc_html($post->post_name);
            $suggestion['post_type'] = esc_html($args['post_type']);
            
            $suggestions[] = $suggestion;
        endforeach;
        
        $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
        echo trim($response);
     
        exit;
	}

	public static function custom_columns( $columns ) {
		if ( ! is_array( $columns ) ) {
			$columns = array();
		}
		foreach($columns as $key => $title) {
			$new[$key] = $title;
		    if ($key == 'title') {
		      	$new['shortcode'] = esc_html__('ShortCode', 'goal-lookbook');
		    }
	  	}
		return $new;
	}

	public static function custom_column( $column, $post_id ) {
		$post = get_post($post_id);

		switch ( $column ) {
	        case 'shortcode' :
                echo '[lookbook slug="'.$post->post_name.'"]';
            break;

	    }
	}
}

GoalLookbook_PostType_Lookbook::init();