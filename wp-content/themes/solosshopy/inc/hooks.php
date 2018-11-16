<?php
/**
 * Theme hooks.
 *
 * @package Solosshopy
 */

// Menu description.
add_filter( 'walker_nav_menu_start_el', 'solosshopy_nav_menu_description', 10, 4 );

// Sidebars classes.
add_filter( 'solosshopy_widget_area_classes', 'solosshopy_set_sidebar_classes', 10, 2 );

// Set footer columns.
add_filter( 'dynamic_sidebar_params', 'solosshopy_get_footer_widget_layout' );

// Adapt default image post format classes to current theme.
add_filter( 'cherry_post_formats_image_css_model', 'solosshopy_add_image_format_classes', 10, 2 );

// Enqueue misc js script.
add_filter( 'solosshopy_theme_script_depends', 'solosshopy_enqueue_misc' );

// Add to toTop and stickUp properties if required.
add_filter( 'solosshopy_theme_script_variables', 'solosshopy_js_vars' );

// Add has/no thumbnail classes for posts.
add_filter( 'post_class', 'solosshopy_post_thumb_classes' );

// Modify a comment form.
add_filter( 'comment_form_defaults', 'solosshopy_modify_comment_form' );

// Reorder comment fields
add_filter( 'comment_form_fields', 'solosshopy_reorder_comment_fields' );

// Additional body classes.
add_filter( 'body_class', 'solosshopy_extra_body_classes' );

// Render macros in text widgets.
add_filter( 'widget_text', 'solosshopy_render_widget_macros' );

// Adds the meta viewport to the header.
add_action( 'wp_head', 'solosshopy_meta_viewport', 0 );

// Customization for `Tag Cloud` widget.
add_filter( 'widget_tag_cloud_args', 'solosshopy_customize_tag_cloud' );

// Changed excerpt more string.
add_filter( 'excerpt_more', 'solosshopy_excerpt_more' );

// Creating wrappers for audio shortcode.
add_filter( 'wp_audio_shortcode', 'solosshopy_audio_shortcode', 10, 5 );
add_filter( 'cherry_get_the_post_audio', 'solosshopy_cherry_get_the_post_audio' );

// Set specific content classes.
add_filter( 'solosshopy_content_classes', 'solosshopy_set_specific_content_classes' );

// Set specific customizer settings.
add_filter( 'theme_mod_sidebar_width', 'solosshopy_woo_sidebar_width' ); // temp
add_filter( 'theme_mod_sidebar_position', 'solosshopy_specific_sidebar_position' ); // temp

// Landing main menu location.
add_filter( 'solosshopy_main_menu_args', 'solosshopy_landing_main_menu_location' );

// Change gallery image size into single post and non-sidebar layout.
add_filter( 'cherry_get_the_post_gallery_defaults', 'solosshopy_post_gallery_img_size', 10, 3 );

// Add invert classes if breadcrumbs sections is darken.
add_filter( 'cherry_breadcrumbs_wrapper_classes', 'solosshopy_cherry_breadcrumbs_wrapper_classes' );

// Add dynamic css function.
add_filter( 'cherry_css_func_list', 'solosshopy_add_dynamic_css_function' );

// Check for theme updates.
add_filter( 'http_request_args', 'solosshopy_disable_wporg_request', 5, 2 );

// Add dynamic css plugins files.
add_filter( 'solosshopy_get_dynamic_css_options', 'solosshopy_add_dynamic_css_plugins_files' );

// Get custom background
add_filter( 'cherry_css_func_list', 'solosshopy_get_custom_background_dynamic_func' );

/**
 * Append description into nav items
 *
 * @param  string $item_output The menu item output.
 * @param  WP_Post $item Menu item object.
 * @param  int $depth Depth of the menu.
 * @param  array $args wp_nav_menu() arguments.
 *
 * @return string
 */
function solosshopy_nav_menu_description( $item_output, $item, $depth, $args ) {

	if ( 'main' !== $args->theme_location || ! $item->description ) {
		return $item_output;
	}

	$descr_enabled = get_theme_mod(
		'header_menu_attributes',
		solosshopy_theme()->customizer->get_default( 'header_menu_attributes' )
	);

	if ( ! $descr_enabled ) {
		return $item_output;
	}

	$current     = $args->link_after . '</a>';
	$description = '<div class="menu-item__desc">' . $item->description . '</div>';
	$item_output = str_replace( $current, $description . $current, $item_output );

	return $item_output;
}

/**
 * Set layout classes for sidebars.
 *
 * @since  1.0.0
 * @uses   solosshopy_get_layout_classes.
 *
 * @param  array $classes Additional classes.
 * @param  string $area_id Sidebar ID.
 *
 * @return array
 */
function solosshopy_set_sidebar_classes( $classes, $area_id ) {

	if ( in_array( $area_id, array( 'sidebar', 'single-service', 'single-project', 'shop-sidebar' ) ) ) {
		if ( $area_id === 'shop-sidebar' ) {
			$classes[] .= 'sidebar';
		}

		return solosshopy_get_layout_classes( 'sidebar', $classes );
	}

	if ( 'footer-area' == $area_id ) {
		$columns = get_theme_mod( 'footer_widget_columns', solosshopy_theme()->customizer->get_default( 'footer_widget_columns' ) );

		if ( '1' !== $columns ) {
			$classes[] = sprintf( 'footer-area--%s-cols', $columns );
		} else {
			$classes[] = 'footer-area--fullwidth';
		}

		$classes[] = 'row';
	}

	return $classes;
}

/**
 * Get footer widgets layout class
 *
 * @since  1.0.0
 *
 * @param  string $params Existing widget classes.
 *
 * @return string
 */
function solosshopy_get_footer_widget_layout( $params ) {

	if ( is_admin() ) {
		return $params;
	}

	if ( empty( $params[0]['id'] ) || 'footer-area' !== $params[0]['id'] ) {
		return $params;
	}

	if ( empty( $params[0]['before_widget'] ) ) {
		return $params;
	}

	$columns = get_theme_mod(
		'footer_widget_columns',
		solosshopy_theme()->customizer->get_default( 'footer_widget_columns' )
	);

	$columns = intval( $columns );
	$classes = 'class="col-xs-12 col-sm-%3$s col-md-%2$s col-lg-%1$s %4$s ';

	switch ( $columns ) {
		case 4:
			$lg_col = 3;
			$md_col = 6;
			$sm_col = 12;
			$extra  = '';
			break;

		case 3:
			$lg_col = 4;
			$md_col = 4;
			$sm_col = 12;
			$extra  = '';
			break;

		case 2:
			$lg_col = 6;
			$md_col = 6;
			$sm_col = 12;
			$extra  = '';
			break;

		default:
			$lg_col = 12;
			$md_col = 12;
			$sm_col = 12;
			$extra  = '';
			break;
	}

	$params[0]['before_widget'] = str_replace(
		'class="',
		sprintf( $classes, $lg_col, $md_col, $sm_col, $extra ),
		$params[0]['before_widget']
	);

	return $params;
}

/**
 * Filter image CSS model
 *
 * @param  array $css_model Default CSS model.
 * @param  array $args Post formats module arguments.
 *
 * @return array
 */
function solosshopy_add_image_format_classes( $css_model, $args ) {
	$blog_featured_image = get_theme_mod( 'blog_featured_image', solosshopy_theme()->customizer->get_default( 'blog_featured_image' ) );
	$blog_layout         = get_theme_mod( 'blog_layout_type', solosshopy_theme()->customizer->get_default( 'blog_layout_type' ) );
	$suffix              = ( 'default' !== $blog_layout ) ? 'fullwidth' : $blog_featured_image;

	$css_model['link'] .= ' post-thumbnail--' . $suffix;

	return $css_model;
}

/**
 * Enqueue misc js script.
 *
 * @param  array $depends Default dependencies.
 *
 * @return array
 */
function solosshopy_enqueue_misc( $depends ) {
	global $is_IE;

	if ( $is_IE ) {
		$depends[] = 'object-fit-images';
	}

	return $depends;
}

/**
 * Add to toTop and stickUp properties if required.
 *
 * @param  array $vars Default variables.
 *
 * @return array
 */
function solosshopy_js_vars( $vars ) {
	$header_menu_sticky = get_theme_mod( 'header_menu_sticky', solosshopy_theme()->customizer->get_default( 'header_menu_sticky' ) );

	if ( $header_menu_sticky && ! wp_is_mobile() ) {
		$vars['stickUp'] = true;
	}

	$totop_visibility = get_theme_mod( 'totop_visibility', solosshopy_theme()->customizer->get_default( 'totop_visibility' ) );

	if ( $totop_visibility ) {
		$vars['toTop'] = true;
	}

	return $vars;
}

/**
 * Add has/no thumbnail classes for posts
 *
 * @param  array $classes Existing classes.
 *
 * @return array
 */
function solosshopy_post_thumb_classes( $classes ) {
	$thumb = 'no-thumb';

	if ( has_post_thumbnail() ) {
		$thumb = 'has-thumb';
	}

	$classes[] = $thumb;

	return $classes;
}

/**
 * Add placeholder attributes for comment form fields.
 *
 * @param  array $args Arguments for comment form.
 *
 * @return array
 */
function solosshopy_modify_comment_form( $args ) {
	$args = wp_parse_args( $args );

	if ( ! isset( $args['format'] ) ) {
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
	}

	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html_req  = ( $req ? " required='required'" : '' );
	$html5     = 'html5' === $args['format'];
	$commenter = wp_get_current_commenter();

	$args['fields']['author'] = '<p class="comment-form-author"><input id="author" class="comment-form__field" name="author" type="text" placeholder="' . esc_html__( 'Your name', 'solosshopy' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['email'] = '<p class="comment-form-email"><input id="email" class="comment-form__field" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Your e-mail', 'solosshopy' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['url'] = '<p class="comment-form-url"><input id="url" class="comment-form__field" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Your website', 'solosshopy' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	$args['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" class="comment-form__field" name="comment" placeholder="' . esc_html__( 'Your comment *', 'solosshopy' ) . '" cols="45" rows="8" aria-required="true" required="required"></textarea></p>';

	$args['title_reply_before'] = '<h5 id="reply-title" class="comment-reply-title">';

	$args['title_reply_after'] = '</h5>';

	$args['title_reply'] = esc_html__( 'Leave a reply', 'solosshopy' );

	$args['label_submit'] = esc_html__( 'Post Comment', 'solosshopy' );

	$args['class_submit'] = esc_attr( 'submit btn btn-accent-1' );

	return $args;
}

/**
 * Reorder comment fields
 *
 * @param  array $fields Comment fields.
 *
 * @return array
 */
function solosshopy_reorder_comment_fields( $fields ) {

	if ( is_singular( 'product' ) ) {
		return $fields;
	}

	$new_fields_order = array();
	$new_order        = array( 'author', 'email', 'url', 'comment' );

	foreach ( $new_order as $key ) {
		$new_fields_order[ $key ] = $fields[ $key ];
		unset( $fields[ $key ] );
	}

	return $new_fields_order;
}

/**
 * Add extra body classes
 *
 * @param  array $classes Existing classes.
 *
 * @return array
 */
function solosshopy_extra_body_classes( $classes ) {
	global $is_IE;

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of ie to browsers IE.
	if ( $is_IE ) {
		$classes[] = 'ie';
	}

	// Adds a options-based classes.
    $header_layout  = solosshopy_get_mod( 'header_container_type', true, 'sanitize_html_class' );
    $content_layout = solosshopy_get_mod( 'content_container_type', true, 'sanitize_html_class' );
    $footer_layout  = solosshopy_get_mod( 'footer_container_type', true, 'sanitize_html_class' );
    $blog_layout    = solosshopy_get_mod( 'blog_layout_type', true, 'sanitize_html_class' );
    $sb_position    = solosshopy_get_mod( 'sidebar_position', true, 'sanitize_html_class' );
    $sidebar        = solosshopy_get_mod( 'sidebar_width', true, 'sanitize_html_class' );
    $header_type    = solosshopy_get_mod( 'header_layout_type', true, 'sanitize_html_class'  );
    $footer_type    = solosshopy_get_mod( 'footer_layout_type', true,  'sanitize_html_class' );
    $word_wrap      = solosshopy_get_mod( 'word_wrap', true, 'sanitize_html_class' );

	return array_merge( $classes, array(
		'header-layout-' . $header_layout,
		'content-layout-' . $content_layout,
		'footer-layout-' . $footer_layout,
		'blog-' . $blog_layout,
		'position-' . $sb_position,
		'sidebar-' . str_replace( '/', '-', $sidebar ),
		'header-' . $header_type,
		'footer-' . $footer_type,
		$word_wrap,
	) );
}

/**
 * Replace macroses in text widget.
 *
 * @param  string $text Default text.
 *
 * @return string
 */
function solosshopy_render_widget_macros( $text ) {
	$uploads = wp_upload_dir();

	$data = array(
		'/%%uploads_url%%/' => $uploads['baseurl'],
		'/%%home_url%%/'    => esc_url( home_url( '/' ) ),
		'/%%theme_url%%/'   => get_template_directory_uri(),
	);

	return preg_replace( array_keys( $data ), array_values( $data ), $text );
}

/**
 * Adds the meta viewport to the header.
 *
 * @since  1.0.1
 */
function solosshopy_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />' . "\n";
}

/**
 * Customization for `Tag Cloud` widget.
 *
 * @since  1.0.1
 *
 * @param  array $args Widget arguments.
 *
 * @return array
 */
function solosshopy_customize_tag_cloud( $args ) {
	$args['smallest'] = 14;
	$args['largest']  = 14;
	$args['unit']     = 'px';

	return $args;
}

/**
 * Replaces `[...]` (appended to automatically generated excerpts) with `...`.
 *
 * @since  1.0.1
 *
 * @param  string $more The string shown within the more link.
 *
 * @return string
 */
function solosshopy_excerpt_more( $more ) {

	if ( is_admin() ) {
		return $more;
	}

	return esc_html__( ' &hellip;', 'solosshopy' );
}

/**
 * Creating wrappers for audio shortcode.
 */
function solosshopy_audio_shortcode( $html, $atts, $audio, $post_id, $library ) {

	$html = sprintf( '<div class="mejs-container-wrapper">%s</div>', $html );

	return $html;
}

/**
 * Creating wrappers for audio post featured content.
 */
function solosshopy_cherry_get_the_post_audio( $result ) {

	$result = sprintf( '<div class="post-format-audio">%s</div>', $result );

	return $result;
}

/**
 * Set specific content classes for blog listing
 */
function solosshopy_set_specific_content_classes( $layout_classes ) {
	$sidebar_position = get_theme_mod( 'sidebar_position' );

	$white_list_post_type = array(
		'post',
		'tm-testimonials',
	);

	if ( ( 'fullwidth' === $sidebar_position && is_singular( $white_list_post_type ) ) ) {
		$layout_classes = array( 'col-xs-12', 'col-lg-8', 'col-lg-push-2' );
	}

	return $layout_classes;
}

/**
 * Customization sidebar width to woo page.
 */
function solosshopy_woo_sidebar_width( $value ) {

	if ( solosshopy_is_woocommerce_activated() && is_woocommerce() ) {
		return '1/4';
	}

	return $value;
}

/**
 * Disable sidebar to single woo product page and 404 page.
 */
function solosshopy_specific_sidebar_position( $value ) {

	if ( ( solosshopy_is_woocommerce_activated() && is_product() ) || is_404() ) {
		return 'fullwidth';
	}

	return $value;
}

/**
 * Landing main menu location.
 */
function solosshopy_landing_main_menu_location( $args ) {

	if ( 'page-templates/landing.php' === get_page_template_slug() ) {
		$args['theme_location'] = 'main_landing';
	}

	return $args;
}

/**
 * Change gallery image size into single post and non-sidebar layout.
 *
 * @param $args
 * @param $post_id
 * @param $post_type
 *
 * @return mixed
 */
function solosshopy_post_gallery_img_size( $args, $post_id, $post_type ) {
	$sidebar_position = get_theme_mod( 'sidebar_position', solosshopy_theme()->customizer->get_default( 'sidebar_position' ) );

	if ( 'fullwidth' == $sidebar_position && is_singular( 'post' ) ) {
		$args['size'] = 'solosshopy-thumb-l';
	}

	return $args;
}

/**
 *  Add invert classes if breadcrumbs sections is darken.
 *
 * @param array $wrapper_classes Classes array.
 *
 * @return array
 */
function solosshopy_cherry_breadcrumbs_wrapper_classes( $wrapper_classes = array() ) {
	$breadcrumbs_bg = get_theme_mod( 'breadcrumbs_bg_color', solosshopy_theme()->customizer->get_default( 'breadcrumbs_bg_color' ) );

	if ( 'dark' === solosshopy_hex_color_is_light_or_dark( $breadcrumbs_bg ) ) {
		$wrapper_classes[] = 'invert';
	}

	return $wrapper_classes;
}

/**
 * Add dynamic css function.
 *
 * @param array $func_list Function list.
 *
 * @return array
 */
function solosshopy_add_dynamic_css_function( $func_list = array() ) {

	$func_list['background_position'] = 'solosshopy_dynamic_css_background_position';

	return $func_list;
}

/**
 * Callback function for dynamic css function `background_position`.
 *
 * @param string $position Background position.
 *
 * @return bool|string
 */
function solosshopy_dynamic_css_background_position( $position ) {
	if ( ! $position ) {
		return false;
	}

	$format = 'background-position: %s';
	$result = sprintf( $format, str_replace( '-', ' ', $position ) );

	return $result;
}

/**
 * Disable requests to wp.org repository for this theme.
 *
 * @link  https://wptheming.com/2014/06/disable-theme-update-checks/
 * @since 1.0.0
 *
 * @param  array $r An array of HTTP request arguments.
 * @param  string $url The request URL.
 *
 * @return array
 */
function solosshopy_disable_wporg_request( $r, $url ) {

	// If it's not a theme update request, bail.
	if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
		return $r;
	}

	// Decode the JSON response.
	$themes = json_decode( $r['body']['themes'] );

	// Remove the active parent and child themes from the check.
	$parent = get_option( 'template' );
	$child  = get_option( 'stylesheet' );

	unset( $themes->themes->$parent );
	unset( $themes->themes->$child );

	// Encode the updated JSON response.
	$r['body']['themes'] = json_encode( $themes );

	return $r;
}

/**
 * Add dynamic css plugins files.
 *
 * @param array $args Dynamic css arguments.
 *
 * @return array
 */
function solosshopy_add_dynamic_css_plugins_files( $args = array() ) {

	$plugins_config = array(
		array(
			'class'            => 'WooCommerce',
			'dynamic_css_path' => 'assets/css/dynamic-woo.css',
		),
		array(
			'class'            => 'Cherry_Team_Members',
			'dynamic_css_path' => 'assets/css/dynamic/plugins/cherry-team-members.css',
		),
		array(
			'class'            => 'Cherry_Services_List',
			'dynamic_css_path' => 'assets/css/dynamic/plugins/cherry-services-list.css',
		),
		array(
			'class'            => 'TM_Testimonials_Plugin',
			'dynamic_css_path' => 'assets/css/dynamic/plugins/cherry-testimonials.css',
		),
		array(
			'class'            => 'Cherry_Projects',
			'dynamic_css_path' => 'assets/css/dynamic/plugins/cherry-project.css',
		),
		array(
			'class'            => 'Polylang',
			'dynamic_css_path' => 'assets/css/dynamic/plugins/polylang.css',
		),
	);

	foreach ( $plugins_config as $plugin ) {

		if ( class_exists( $plugin['class'] ) ) {
			$args['css_files'][] = solosshopy_get_locate_template( $plugin['dynamic_css_path'] );
		}

	}

	return $args;
}

/**
 * Get custom background.
 *
 * @param array $func_list Func list.
 *
 * @return array
 */
function solosshopy_get_custom_background_dynamic_func( $func_list ) {
	$func_list['custom_background'] = 'solosshopy_get_custom_background';

	return $func_list;
}

function solosshopy_get_custom_background( $option = '' ) {
	$background_color = get_background_color();

	if ( ! empty( $background_color ) ) {
		if ( 'color' == $option ) {
			return sprintf( '#%s', $background_color );
		}

		return sprintf( 'background-color:#%s;', $background_color );
	}
}

// Modify `Muli` google font to revolution-slider.
add_filter( 'revslider_operations_getArrFontFamilys', 'solosshopy_modify_muli_google_font_to_revslider' );

/**
 * Modify `Muli` google font to revolution-slider.
 *
 * @param array $fonts Fonts.
 *
 * @return array
 */
function solosshopy_modify_muli_google_font_to_revslider( $fonts = array() ) {

	$prepare_fonts = array();

	foreach ( $fonts as $key => $value ) {
		$prepare_fonts[ $value['label'] ] = $value;
	}

	$prepare_fonts['Montserrat']['variants'] = array(
		'200',
		'200italic',
		'300',
		'300italic',
		'400',
		'italic',
		'500',
		'500italic',
		'600',
		'600italic',
		'700',
		'700italic',
		'800',
		'800italic',
		'900',
		'900italic',
	);

	$prepare_fonts['Montserrat']['subsets'] = array(
		'latin',
		'latin-ext',
	);

	$fonts = array_values( $prepare_fonts );

	return $fonts;
}


add_filter( 'jet_elements/carousel/available_arrows/next', 'solosshopy_jet_elements_carousel_arrow_next' );
add_filter( 'jet_elements/carousel/available_arrows/prev', 'solosshopy_jet_elements_carousel_arrow_prev' );

function solosshopy_jet_elements_carousel_arrow_next( $args = array() ){
    $args['nc-icon-mini arrows-1_tail-triangle-right'] = esc_html__( 'Triangle right', 'solosshopy' );
    return $args;
}

function solosshopy_jet_elements_carousel_arrow_prev( $args = array() ){
    $args['nc-icon-mini arrows-1_tail-triangle-left'] = esc_html__( 'Triangle left', 'solosshopy' );
    return $args;
}