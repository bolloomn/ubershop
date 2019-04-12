<?php if ( ! defined('PUKKA_VERSION')) exit('No direct script access allowed');

	/**
	* Saves post meta, separetely, for preview
	* Triggered when 'Preview changes' button is clicked
	*
	* @param string $url preview URL
	*
	* @return string $url preview URL
	*/
	/*
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
	*/

	function pukka_body_classes($classes) {
		global $post;
		$has_sidebar = false;
		
		// need to check user agent, and then set class to desktop or mobile
		$classes[] = 'desktop';
		
		$menu_pos = pukka_get_option('main_menu_position');
		$classes[] = 'menu-' . $menu_pos;
		
		if('left' == $menu_pos) {
			$classes[] = 'popup';
		}
		
		// page specific sidebars
		if( is_page() ) {
			$post_id = $post->ID;
		}

		if( function_exists('is_shop') && is_shop() ){
			$post_id = wc_get_page_id( 'shop' );
		}
		if( !empty($post_id) ){
			$overwrite_sidebars = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX .'overwrite_sidebars', true);
			$sidebar_left = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX .'page_left_sidebar_id', true);
			$sidebar_right = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX .'page_right_sidebar_id', true);
		}
		else{
			$overwrite_sidebars = '';
			$sidebar_left = '';
			$sidebar_right = '';
		}

		// theme settings
		$settings = (object)pukka_theme_style_settings();

		// left sidebar
		$left_sidebar_enabled = apply_filters('pukka_left_sidebar', $settings->left_sidebar);

		// right sidebar
		$right_sidebar_enabled = apply_filters('pukka_right_sidebar', $settings->right_sidebar);

		/*
		// left sidebar
		$left_sidebar_enabled = (('left' == $menu_pos || 'on' == pukka_get_option('sidebar_left_enable')) && 'on' != $overwrite_sidebars);
		$left_sidebar_enabled = apply_filters('pukka_left_sidebar', $left_sidebar_enabled);

		// right sidebar
		$right_sidebar_enabled = pukka_get_option('sidebar_right_enable') == 'on' ? true : false;
		$right_sidebar_enabled = apply_filters('pukka_right_sidebar', $right_sidebar_enabled);
		*/

		// page width
		$page_width = pukka_get_option('default_pages_width');

		// grid enabled
		$posts_col_no = pukka_get_option('posts_col_no') != '' ? (int)pukka_get_option('posts_col_no') : 1;
		$posts_col_no = apply_filters('pukka_category_column_no', $posts_col_no);

		$grid_enabled = $posts_col_no > 1 ? true : false;

		// secondary menu (top stripe)
		$enable_secondary_menu = pukka_get_option('enable_secondary_menu') == 'on' ? true : false;
		$enable_secondary_menu = apply_filters('pukka_secondary_menu', $enable_secondary_menu);

		// page specific override
		if( (is_page() || is_singular())
			&& (get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'page_width', true) != '' && get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'page_width', true) != 'default')
		){
			$page_width = get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'page_width', true);
		}

		$page_width = apply_filters('pukka_page_width', $page_width);

		if( ($left_sidebar_enabled && 'on' != $overwrite_sidebars) || ('on' == $overwrite_sidebars && '' != $sidebar_left) ){
			$classes[] = 'has-sidebar-left'; 
			$has_sidebar = true;
		}
		
		if( ($right_sidebar_enabled && 'on' != $overwrite_sidebars) || ('on' == $overwrite_sidebars && '' != $sidebar_right) ){
			$classes[] = 'has-sidebar-right';
			$has_sidebar = true;
		}

		if( $grid_enabled ){
			$classes[] = 'has-grid';
		}
		
		if($has_sidebar){
			$classes[] = 'has-sidebar';
		}

		if( !empty($page_width) ){
			$classes[] = 'width-' . $page_width;
		}

		if( $enable_secondary_menu ){
			$classes[] = 'secondary-menu';
		}

		return $classes;
	}

	add_filter('body_class', 'pukka_body_classes');

	if( !function_exists('pukka_box_social') ) :
	/**
	 * Prints social share buttons
	 *
	 * @since Pukka 1.0
	 */
	function pukka_social_share(){
		global $post;

		if( pukka_get_option('enable_product_share') != 'on'){
			return;
		}

		$url = get_permalink();
		$title = get_the_title();
		$description = wp_strip_all_tags(apply_filters('excerpt', $post->post_excerpt), true);
		$image = '';
		$networks = array(
						'fb' => 'facebook',
						'tw' => 'twitter',
//						'gp' => 'google-plus',
						// 'in' => 'linkedin',
						'pt' => 'pinterest'
					);
		$share_html = '';

		if( has_post_thumbnail() ){
			$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
			$image = $thumb[0];
		}

		$data = sprintf(
					'data-url="%s" data-title="%s" data-desc="%s" data-image="%s"',
					esc_attr($url), // data-url
					esc_attr($title), // data-title
					esc_attr($description), // data-desc
					esc_attr($image) // data-image
				);

		foreach( $networks as $k => $v){
			$share_html .= sprintf(
						'<a href="#" class="pukka-share pukka-share-%s fa fa-%s" data-network="%s"></a>',
						esc_attr($k), // css class
						esc_attr($v), // css class
						esc_attr($k) // data-network
					);
		}

		echo '<div class="social-share-buttons" '. $data .'>'. $share_html .'</div>';

	}
	add_action('woocommerce_share', 'pukka_social_share');
	endif; // if( !function_exists('pukka_social_share') ) :
	
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
		
		$product_img_size = get_option('shop_catalog_image_size');
		$settings['product_column_width'] = $product_img_size['width'] + 20;
		
		return $settings;
	}



	/* BEGIN: term meta */
	// @thanks: http://pipinsplugins.com/adding-custom-meta-fields-to-taxonomies/

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
		<th scope="row" valign="top"><label for="term_meta[cat_header]"><?php _e( 'Top content', 'pukka' ); ?></label></th>
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
	
	
	/**
	*	This function generates custom css based on theme settings	
	*******************************************************************/
	function generate_theme_css(){
		$css = "/* This file is automatically generated when saving theme options. " .
		"\n Do not try to change content of this file because it will be overwritten next time you save theme options. */";
		
		// Theme color scheme
		$css .= "\n\n\n /* Theme color scheme */\n";
		
		$primary_color = pukka_get_option('theme_primary_color');
			
		if(!empty($primary_color)){
			$css .= "
					\n /**
					\n *	Color
					\n *************************************************/
					\n .entry-content a,
					\n .dm-wrap a,
					\n .entry-content a:hover, 
					\n .dm-wrap a:hover, 
					\n .entry-content a:visited,
					\n .dm-wrap a:visited,
					\n .single-price .price, 
					\n .single_variation .price, 
					\n .product-subtotal,
					\n .comment-text a,
					\n .comment-reply-link, 
					\n .logged-in-as a,
					\n #cancel-comment-reply-link,
					\n .woocommerce-info a,
					\n #footer .tagcloud a:hover,
					\n .txt-dk,
					\n .author-links a:hover {
					\n 	color: {$primary_color};
					\n }
					\n
					\n /**
					\n *	BG Color
					\n ***************************************/
					\n .button,
					\n a.button,
					\n a.button:hover,
					\n a.button:visited,
					\n .button a,
					\n .button a:hover,
					\n .button a:visited,
					\n button,
					\n input[type='button'],
					\n input[type='reset'],
					\n input[type='submit'], 
					\n .added_to_cart, 
					\n .added_to_cart:visited ,
					\n #bottom-stripe,
					\n .pukka-featured-post-widget .read-more,
					\n .pukka-latest-posts-widget .latest-date,
					\n .woocommerce-tabs .tabs .active,
					\n .ui-slider-range,
					\n .ui-slider-handle,
					\n .buttons, 
					\n a.buttons, 
					\n a.buttons:hover, 
					\n a.buttons:visited, 
					\n .buttons a,
					\n .buttons a:visited, 
					\n .buttons a:hover,
					\n .active,
					\n .active a,
					\n .active a:hover,
					\n .active a:visited,
					\n a.active,
					\n a.active:hover,
					\n a.active:visited ,
					\n .tagcloud a:hover {    
					\n 	background-color: {$primary_color};
					\n	color: #ffffff;
					\n }
					\n 	
					\n a.button.checkout {
					\n 	background-color: {$primary_color} !important;
					\n	color: #ffffff;
					\n }
					\n 
					\n /**
					\n *	Border Color
					\n *******************************************/
					\n .tagcloud a:hover,
					\n .sticky {
					\n 	border-color: {$primary_color};
					\n }
					\n 
					\n blockquote, q {
					\n 	border-left-color: {$primary_color};
					\n }
					\n 
					\n #shop-toggle {    
					\n 	border-top-color: {$primary_color};
					\n }";
		}
		
		$secondary_color = pukka_get_option('theme_secondary_color');
		
		if(!empty($secondary_color)){
			$css .= "\n 
					\n /**
					\n *	Color
					\n *******************************************/
					\n .star-rating,
					\n .txt-lt {
					\n 	color: {$secondary_color};
					\n }
					\n 
					\n /**
					\n *	Background Color
					\n ******************************************/
					\n 
					\n #top-link,
					\n .full #menu-secondary,
					\n .full #menu-secondary li ul,
					\n #footer,
					\n #secondary-footer,
					\n .widget_ninja_forms_widget,
					\n .masonry .product-block:hover .price,
					\n .price_slider,
					\n .buttons-lt, 
					\n a.buttons-lt, 
					\n a.buttons-lt:hover, 
					\n a.buttons-lt:visited, 
					\n .buttons-lt a,
					\n .buttons-lt a:visited, 
					\n .buttons-lt a:hover,
					\n .open .acc-box-title,
					\n .tabs-title li.current h4 {
					\n 	background-color: {$secondary_color};
					\n	color: #ffffff;
					\n }
					\n 
					\n a.button.wc-forward {
					\n 	background-color: {$secondary_color} !important;
					\n	color: #ffffff;
					\n }
					\n 
					\n @media all and (max-width: 520px) {
					\n 	.product-block .price,
					\n 	.product-block:hover .price,
					\n 	.masonry .product-block:hover .price,
					\n 	.masonry .product-block:hover .price 	{
					\n 		background-color: {$secondary_color};
					\n		color: #ffffff;
					\n 	}
					\n }";
		}
		
		// additional css from color scheme
		$css .= "\n\n\n /* Color scheme specific css */";
		$theme_style = pukka_get_option(PUKKA_THEME_COLORSCHEME_NAME);
		global $theme_style_settings;
		if(!empty($theme_style) && 'none' != $theme_style && !empty($theme_style_settings[$theme_style])){
			foreach($theme_style_settings[$theme_style] as $key => $val){
				if(empty($val['selector'])) continue;
				$css .= "\n" . $val['selector'] . '{';
				foreach($val['attributes'] as $attribute){
					if(!empty($attribute['key'])){
						$this->css .= $attribute['key'] . ':' . $attribute['value'] . ';';
					}
				}
				$css .= "}\n";
			}
		}
						
		// set sidebars and stuff
		$css .= "\n\n\n /* Theme sidebars and stuff */\n";		
		global $pukka_js;
			
		$settings = (object)pukka_theme_style_settings();

		$left_sidebar_enabled  = apply_filters( 'pukka_left_sidebar', $settings->left_sidebar );
		$right_sidebar_enabled = apply_filters( 'pukka_right_sidebar', $settings->right_sidebar );

		$css .= "\n .has-sidebar-left div#main { width: calc( 100% - " . ($settings->sidebar_left_width + 2*$settings->sidebar_margins) . "px ); }";
		// $css .= "\n .has-sidebar-left #main { padding-left: " . ($settings->sidebar_left_width + $settings->sidebar_margins) . "px; }";
		$css .= "\n #sidebar-left { width: " . $settings->sidebar_left_width  . "px; }";
		$css .= "\n @media all and (max-width: 1024px) {";
		$css .= "\n	.has-sidebar-left div#main { width: 100%; }";
		$css .= "\n }";
	
	
		$css .= "\n .has-sidebar-right div#main { width: calc( 100% - " . ($settings->sidebar_right_width + 2*$settings->sidebar_margins) . "px ); }";
		$css .= "\n #sidebar-right { width: " . $settings->sidebar_right_width . "px; }";
		$css .= "\n @media all and (max-width: 1024px) {";
		$css .= "\n	.has-sidebar-right div#main { width: 100%; }";
		$css .= "\n	#sidebar-right { width: 100%; }";
		$css .= "\n }";

		$css .= "\n .has-sidebar-left.has-sidebar-right div#main { width: calc( 100% - " . ($settings->sidebar_right_width + $settings->sidebar_left_width + 2*$settings->sidebar_margins) . "px ); }";
		$css .= "\n @media all and (max-width: 1024px) {";
		$css .= "\n	.has-sidebar-left.has-sidebar-right div#main { width: 100%; }";
		$css .= "\n }";

	
		
		$text_color = pukka_get_option('sidebar_text_color');
		if(!empty($text_color)){
			$css .= "\n .sidebar {color: $text_color !important;}";
		}
		
		
		$link_color = pukka_get_option('sidebar_link_color');
		if(!empty($link_color)){
			$css .= "\n .sidebar a {color: $link_color !important;}";
		}
		
		$link_hover = pukka_get_option('sidebar_link_hover');
		if(!empty($link_hover)){
			$css .= "\n .sidebar a:hover {color: $link_hover !important;}";
		}
		
		// $product_img_size = get_option('shop_catalog_image_size');
		// if(!empty($product_img_size)){
		// 	$width = $product_img_size['width'] - 30;
		// 	$height = round($width * $product_img_size['height'] / $product_img_size['width']);
		// 	$content_height = pukka_get_option('product_block_content_height');
		// 	if(empty($content_height)){
		// 		$content_height = 135;
		// 	}
		// 	$css .= "\n .product-image, .product-image img { min-width: " . round($width) . "px; min-height: " . $height . "px; }";
		// 	// .product-block size needs to be set so that masonry layout works properly.
		// 	// $height is the height of image in block, and 132 is the size of the rest 
		// 	// of the content in block.
		// 	$css .= "\n .product-block { height: " . ($content_height + $height) . "px; width: {$product_img_size['width']}px;}";
		// 	$css .= "\n .woocommerce.columns-2 {max-width: " . 2*$settings->product_column_width . "px;}";
		// 	$css .= "\n .woocommerce.columns-3 {max-width: " . 3*$settings->product_column_width . "px;}";
		// 	$css .= "\n .woocommerce.columns-4 {max-width: " . 4*$settings->product_column_width . "px;}";
		// 	$css .= "\n .woocommerce.columns-5 {max-width: " . 5*$settings->product_column_width . "px;}";
		// 	$css .= "\n .woocommerce.columns-6 {max-width: " . 6*$settings->product_column_width . "px;}";
		// }
		// // set product column width
		// $pukka_js['product_column_width'] = $settings->product_column_width;

				
		// set theme fonts etc...
		$elements = array(
							'title' => '.entry-title', 
							'text' => 'body, .author-name, .author-name a', 
							'h1' => '.entry-content h1', 
							'h2' => '.entry-content h2', 
							'h3' => '.entry-content h3', 
							'h4' => '.entry-content h4', 
							'h5' => '.entry-content h5', 
							'h6' => '.entry-content h6',
						);
		$css .= "\n\n\n /* Theme Fonts */\n";
		$cyrillic_char_set = pukka_get_option('enable_cyrillic_char_set');
		
		foreach($elements as $key => $element){
			$css .= "\n{$element} {";
			
			$font = pukka_get_option($key . '_font');
			
			if(!empty($font)){
				$css .= "\n font-family: '" . str_replace('+', ' ', $font) . "';";
			}
									
			$font_size = pukka_get_option($key . '_font_size');
			if(!empty($font_size)){
				$css .= "\n font-size: {$font_size}px;";
			}
			$font_weight = pukka_get_option($key . '_font_weight');
			if(!empty($font_weight)){
				$css .= "\n font-weight: {$font_weight};";
			}
							
			$font_color = pukka_get_option($key . '_font_color');
			if(!empty($font_color)){
				$css .= "\n color: {$font_color};";
			}
			
			$css .= "\n}\n";
		}
		
		$css .= "\n .headings, h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a,";
		$css .= "h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited, ";
		$css .= ".product_list_widget li > a, .product-categories li, .widget_archive li, .widget_recent_entries li,";
		$css .= ".widget-title, .widget-title a, .widget-title a:visited, .woocommerce-breadcrumb {";
		$font = pukka_get_option('title_font');
		if(!empty($font)){			
			$css .= "\n font-family: '" . str_replace('+', ' ', $font) . "';";
		}
		$font_color = pukka_get_option('title_font_color');
		if(!empty($font_color)){
			$css .= "\n color: {$font_color};";
		}
		$css .= "\n }";
		
		return $css;
	}
	