<?php if ( ! defined('PUKKA_VERSION')) exit('No direct script access allowed');

	/**
	* Saves post meta, separetely, for preview
	* Triggered when 'Preview changes' button is clicked
	*
	* @param string $url preview URL
	*
	* @return string $url preview URL
	*/
	function pukka_save_preview($url){
		global $post;

		// preview
		$pukka_meta_boxes = array();
		$pukka_meta_boxes = apply_filters('pukka_add_meta_boxes', $pukka_meta_boxes);

		// save post meta
		foreach( $pukka_meta_boxes as $meta_box ){
			if( isset($meta_box['post_type']) && $meta_box['post_type'] == 'page' ){
				foreach( (array)$meta_box['fields'] as $field ){
					// save revision meta

					$meta_key = '_rev'. $field['id'];

					$old = get_post_meta($post->ID, $meta_key, true);
					$new = isset($_POST['pukka'][$field['id']]) ? $_POST['pukka'][$field['id']] : '';

					// 'off' value si passed if checkbox is not checked
					if( $new == 'off' ){
						$new = '';
					}

					if ($new && $new != $old) {
						update_post_meta($post->ID, $meta_key, wp_filter_kses($new));

					} elseif ('' == $new && $old) {
						delete_post_meta($post->ID, $meta_key, $old);
					}
				}

			}
		}

		// save page builder
		if( class_exists('Dynamic_Meta') ){
			$new_dm = pukka_set_dm_array();
			$old_dm = get_post_meta($post->ID, '_rev_pukka_dynamic_meta_box', true);
			
			if( $new_dm != '' ){
				// save revision DM meta
				update_post_meta($post->ID, '_rev_pukka_dynamic_meta_box', $new_dm);
			}
			elseif( $old_dm != '' ){
				delete_post_meta($post->ID, '_rev_pukka_dynamic_meta_box');
			}
		}

		// return preview url
		return $url;
	}
	add_action('preview_post_link', 'pukka_save_preview');

	/**
	 * AJAX callback, used for search autocomplete
	 */
	/*
	function ajax_search_autocomplete() {
		global $wpdb;

		$args = array(
			'term' => empty($_GET['term']) ? '' : $_GET['term'],
			'no_post_ids' => '',
			'post_ids' => '',
			'cat' => empty($_GET['cat']) ? '' : $_GET['cat'],
			'meta' => '',
			'lang' => empty($_GET['lang']) ? '' : $_GET['lang'],
			'post_type' => '',
			'post_status' => 'publish'
		);

		$posts = pukka_get_posts_by($args);
		$list = array();
		foreach($posts as $post){
			$list[] = array(
				'label' => $post->post_title . ' (' . $post->post_type . ')',
				'value' => $post->post_title,
				'url' => get_permalink($post->ID),
			);
		}

		die(json_encode($list));
	}
	add_action('wp_ajax_pukka_search_autocomplete', 'ajax_search_autocomplete');
	add_action('wp_ajax_nopriv_pukka_search_autocomplete', 'ajax_search_autocomplete');
	*/
	
	function pukka_body_classes($classes) {
		global $post;
		$has_sidebar = false;
		
		if('left' == pukka_get_option('main_menu_position')) {
			$classes[] = 'popup';
		}
		
		if('left' == pukka_get_option('main_menu_position') || 'on' == pukka_get_option('sidebar_left_enable')){
			$classes[] = 'has-sidebar-left'; 
			$has_sidebar = true;
		}
		
		if('on' == pukka_get_option('sidebar_right_enable')){
			$classes[] = 'has-sidebar-right';
			$has_sidebar = true;
		}

		if( 'on' == pukka_get_option('enable_grid') ){
			$classes[] = 'has-grid';
		}
		
		if($has_sidebar){
			$classes[] = 'has-sidebar';
		}
				
		
		/*
		* TODO: adjust for UberMenu
		*/
		if(function_exists('uberMenu_direct')){
			$uber_menus = get_option('wp-mega-menu-nav-locations');
			if(in_array('primary', $uber_menus)){			
				$classes[] = "has-ubermenu";
				$classes[] = "ubermenu-primary";
			}
			if(in_array('secondary', $uber_menus)){			
				$classes[] = "has-ubermenu";
				$classes[] = "ubermenu-secondary";
			}
		}
		
		if(is_page() || is_singular()){
			$page_width = get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'page_width', true);
			if(!empty($page_width)){
				$classes[] = 'width-' . $page_width;
			}
		}

		return $classes;
	}

	add_filter('body_class', 'pukka_body_classes');
	
	/* Convert hexdec color string to rgb(a) string */
	/*
	function hex2rgba($color, $opacity = false) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if(empty($color))
			  return $default; 

		//Sanitize $color if "#" is provided 
			if ($color[0] == '#' ) {
				$color = substr( $color, 1 );
			}

			//Check if color has 6 or 3 characters and get values
			if (strlen($color) == 6) {
					$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
					$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
					return $default;
			}

			//Convert hexadec to rgb
			$rgb =  array_map('hexdec', $hex);

			//Check if opacity is set(rgba or rgb)
			if($opacity){
				if(abs($opacity) > 1)
					$opacity = 1.0;
				$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
			} else {
				$output = 'rgb('.implode(",",$rgb).')';
			}

			//Return rgb(a) color string
			return $output;
	}
	
	// changes any given hex color to a similar lighter or darker shade
	function similar_color($color, $change = 1){
		if(empty($color)) return '#000000';
		
		if ($color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}
		
		for($i = 0; $i < strlen($color); $i++){
			$val = hexdec($color[$i]) + $change;
			if($val < 0){
				$val = 0;
			}
			if($val > 15){
				$val = 15;
			}
			$color[$i] = dechex($val);
		}
		
		return '#' . $color;
	}
	*/
	
	
	function pukka_theme_style_settings(){
		$settings = array();
		
		$settings['left_sidebar'] = ('on' == pukka_get_option('sidebar_left_enable') ||  'left' == pukka_get_option('main_menu_position')) ? true : false;
		$settings['right_sidebar'] = 'on' == pukka_get_option('sidebar_right_enable') ? true : false;			
		
		$settings['sidebar_left_width'] = pukka_get_option('sidebar_left_width');
		if(empty($settings['sidebar_left_width'])){
			$settings['sidebar_left_width'] = pukka_get_option_default('sidebar_left_width');
		}
		
		$settings['sidebar_right_width'] = pukka_get_option('sidebar_right_width');
		if(empty($settings['sidebar_right_width'])){
			$settings['sidebar_right_width'] = pukka_get_option_default('sidebar_right_width');
		}
		
		$settings['sidebar_margins'] = 10;
		
		$settings['product_column_width'] = 245;
		
		return $settings;
	}



	/* BEGIN: term meta */
	// @thanks: http://pukkasplugins.com/adding-custom-meta-fields-to-taxonomies/

	// Add term page
	function pukka_taxonomy_add_new_meta_field() {
		// this will add the custom meta field to the add new term page
		?>
		<div class="form-field">
			<label for="term_meta[cat_header]"><?php _e( 'Top content', 'pukka' ); ?></label>
			<textarea name="term_meta[cat_header]" id="term_meta[cat_header]"></textarea>
			<p class="description"><?php _e( 'Enter a value for this field. Shortcodes are allowed. This will be displayed at top of the category.','pukka' ); ?></p>
		</div>
	<?php
	}
	add_action( 'product_cat_add_form_fields', 'pukka_taxonomy_add_new_meta_field', 10, 2 );

	// Edit term page
	function pukka_taxonomy_edit_meta_field($term) {
	 
		// put the term ID into a variable
		$t_id = $term->term_id;
	 
		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta = get_option( "taxonomy_$t_id" ); ?>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[cat_hedaer]"><?php _e( 'Top content', 'pukka' ); ?></label></th>
			<td>
				<textarea name="term_meta[cat_header]" id="term_meta[cat_header]"><?php echo esc_attr( $term_meta['cat_header'] ) ? esc_attr( $term_meta['cat_header'] ) : ''; ?></textarea>
				<p class="description"><?php _e( 'Enter a value for this field. Shortcodes are allowed. This will be displayed at top of the category.','pukka' ); ?></p>
			</td>
		</tr>
	<?php
	}
	add_action( 'product_cat_edit_form_fields', 'pukka_taxonomy_edit_meta_field', 10, 2 );

	// Save extra taxonomy fields callback function.
	function save_taxonomy_custom_meta( $term_id ) {
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
			// Save the option array.
			update_option( "taxonomy_$t_id", $term_meta );
		}
	}  
	add_action( 'edited_product_cat', 'save_taxonomy_custom_meta', 10, 2 );  
	add_action( 'create_product_cat', 'save_taxonomy_custom_meta', 10, 2 );

	/* END: term meta */