<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Solosshopy
 */

/**
 * Sidebar position
 */
add_filter( 'theme_mod_sidebar_position', 'solosshopy_set_post_meta_value' );

/**
 * Header container type
 */
add_filter( 'theme_mod_header_container_type', 'solosshopy_set_post_meta_value' );

/**
 * Content container type
 */
add_filter( 'theme_mod_content_container_type', 'solosshopy_set_post_meta_value' );

/**
 * Footer container type
 */
add_filter( 'theme_mod_footer_container_type', 'solosshopy_set_post_meta_value' );

/**
 * Header layout type
 */
add_filter( 'theme_mod_header_layout_type', 'solosshopy_set_post_meta_value' );

/**
 * Header transparent layout
 */
add_filter( 'theme_mod_header_transparent_layout', 'solosshopy_pre_set_post_meta_value' );

/**
 * Header invert color scheme
 */
add_filter( 'theme_mod_header_invert_color_scheme', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable breadcrumbs
 */
add_filter( 'theme_mod_breadcrumbs_visibillity', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable top panel
 */
add_filter( 'theme_mod_top_panel_visibility', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable header contact block
 */
add_filter( 'theme_mod_header_top_panel_contact_block_visibility', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable header cta button
 */
add_filter( 'theme_mod_header_btn_visibility', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable header contact block
 */
add_filter( 'theme_mod_header_contact_block_visibility', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable header search
 */
add_filter( 'theme_mod_header_search', 'solosshopy_pre_set_post_meta_value' );

/**
 * Menu style
 */
add_filter( 'theme_mod_header_menu_style', 'solosshopy_pre_set_post_meta_value' );

/**
 * Footer layout type
 */
add_filter( 'theme_mod_footer_layout_type', 'solosshopy_set_post_meta_value' );

/**
 * Enable/disable footer widget area
 */
add_filter( 'theme_mod_footer_widget_area_visibility', 'solosshopy_pre_set_post_meta_value' );

/**
 * Enable/disable footer contact block
 */
add_filter( 'theme_mod_footer_contact_block_visibility', 'solosshopy_pre_set_post_meta_value' );

/**
 * Navigation section position ( header style-5 ).
 */
add_filter( 'theme_mod_header_nav_panel_position', 'solosshopy_set_post_meta_value' );


/**
 * Set post meta.
 */
function solosshopy_pre_set_post_meta_value( $value ) {

	$value = solosshopy_set_post_meta_value( $value );

	if ( 'true' === $value || 'false' === $value  ) {
		return wp_validate_boolean( $value );
	}

	return $value;
}

/**
 * Set post specific meta value.
 *
 * @param  string $value Default meta-value.
 * @return string
 */
function solosshopy_set_post_meta_value( $value ) {
	$queried_obj = solosshopy_get_queried_obj();

	if ( ! $queried_obj ) {
		return $value;
	}

	$meta_key   = 'solosshopy_' . str_replace( 'theme_mod_', '', current_filter() );
	$meta_value = get_post_meta( $queried_obj, $meta_key, true );

	if ( ! $meta_value || 'inherit' === $meta_value ) {
		return $value;
	}

	return $meta_value;
}

/**
 * Get queried object.
 *
 * @return string|boolean
 */
function solosshopy_get_queried_obj() {
	$queried_obj = apply_filters( 'solosshopy_queried_object_id', false );

	if ( ! $queried_obj && ! solosshopy_maybe_need_rewrite_mod() ) {
		return false;
	}

	$queried_obj = is_home() ? get_option( 'page_for_posts' ) : $queried_obj;
	$queried_obj = ! $queried_obj ? get_the_id() : $queried_obj;

	return $queried_obj;
}

/**
 * Check if we need to try rewrite theme mod or not
 *
 * @return boolean
 */
function solosshopy_maybe_need_rewrite_mod() {

	if ( is_front_page() && 'page' !== get_option( 'show_on_front' ) ) {
		return false;
	}

	if ( is_home() && 'page' == get_option( 'show_on_front' ) ) {
		return true;
	}

	if ( ! is_singular() ) {
		return false;
	}

	return true;
}

/**
 * Render existing macros in passed string.
 *
 * @since  1.0.0
 * @param  string $string String to parse.
 * @return string
 */
function solosshopy_render_macros( $string ) {

	static $macros;

	if ( ! $macros ) {
		$macros = apply_filters( 'solosshopy_data_macros', array(
			'/%%year%%/'      => date( 'Y' ),
			'/%%date%%/'      => date( get_option( 'date_format' ) ),
			'/%%site-name%%/' => get_bloginfo( 'name' ),
			'/%%home_url%%/'  => esc_url( home_url( '/' ) ),
			'/%%theme_url%%/' => get_stylesheet_directory_uri(),
		) );
	}

	return preg_replace( array_keys( $macros ), array_values( $macros ), $string );
}

/**
 * Render font icons in content
 *
 * @param  string $content Content to render.
 * @return string
 */
function solosshopy_render_icons( $content ) {
	$icons     = solosshopy_get_render_icons_set();
	$icons_set = implode( '|', array_keys( $icons ) );

	$regex = '/icon:(' . $icons_set . ')?:?([a-zA-Z0-9-_]+)/';

	return preg_replace_callback( $regex, 'solosshopy_render_icons_callback', $content );
}

/**
 * Callback for icons render.
 *
 * @param  array $matches Search matches array.
 * @return string
 */
function solosshopy_render_icons_callback( $matches ) {

	if ( empty( $matches[1] ) && empty( $matches[2] ) ) {
		return $matches[0];
	}

	if ( empty( $matches[1] ) ) {
		return sprintf( '<i class="fa fa-%s"></i>', $matches[2] );
	}

	$icons = solosshopy_get_render_icons_set();

	if ( ! isset( $icons[ $matches[1] ] ) ) {
		return $matches[0];
	}

	return sprintf( $icons[ $matches[1] ], $matches[2] );
}

/**
 * Get list of icons to render.
 *
 * @return array
 */
function solosshopy_get_render_icons_set() {
	return apply_filters( 'solosshopy_render_icons_set', array(
		'fa' => '<i class="fa fa-%s"></i>',
	) );
}

/**
 * Replace %s with theme URL.
 *
 * @param  string $url Formatted URL to parse.
 * @return string
 */
function solosshopy_render_theme_url( $url ) {
	$path = apply_filters( 'solosshopy_render_theme_url_path', get_template_directory_uri() );
	return esc_url( sprintf( $url, $path ) );
}

/**
 * Get image ID by URL.
 *
 * @param  string $image_src Image URL to search it in database.
 * @return int|bool false
 */
function solosshopy_get_image_id_by_url( $image_src ) {
	global $wpdb;

	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid = %s";
	$id    = $wpdb->get_var( $wpdb->prepare( $query, esc_url( $image_src ) ) );

	return $id;
}

/**
 * Check if passed meta data is visible in current context.
 *
 * @since  1.0.0
 * @param  string $meta    Meta setting to check.
 * @param  string $context Current post context - 'single' or 'loop'.
 * @return bool
 */
function solosshopy_is_meta_visible( $meta, $context = 'loop' ) {

	if ( ! $meta ) {
		return false;
	}

	$meta_enabled = get_theme_mod( $meta, solosshopy_theme()->customizer->get_default( $meta ) );

	switch ( $context ) {

		case 'loop':

			if ( ! is_single() && $meta_enabled ) {
				return true;
			} else {
				return false;
			}

		case 'single':

			if ( is_single() && $meta_enabled ) {
				return true;
			} else {
				return false;
			}
	}

	return false;
}

/**
 * Get post thumbnail size.
 *
 * @param array $args Arguments.
 *
 * @return array
 */
function solosshopy_post_thumbnail_size( $args = array() ) {
	global $wp_query;

	$sidebar_position = get_theme_mod( 'sidebar_position', solosshopy_theme()->customizer->get_default( 'sidebar_position' ) );

	$args = wp_parse_args( $args, array(
		'small'        => 'post-thumbnail',
		'fullwidth'    => ( 'fullwidth' !== $sidebar_position ) ? 'solosshopy-thumb-l' : 'solosshopy-thumb-xl',
		'masonry'      => 'solosshopy-thumb-masonry',
		'justify'      => 'solosshopy-thumb-l-2',
		'class_prefix' => '',
	) );

	$layout               = get_theme_mod( 'blog_layout_type', solosshopy_theme()->customizer->get_default( 'blog_layout_type' ) );
	$size_option          = get_theme_mod( 'blog_featured_image', solosshopy_theme()->customizer->get_default( 'blog_featured_image' ) );
	$blog_layout_columns  = get_theme_mod( 'blog_layout_columns', solosshopy_theme()->customizer->get_default( 'blog_layout_columns' ) );

	$size                 = $args[ $size_option ];
	$link_class           = sanitize_html_class( $args['class_prefix'] . $size_option );
	$format               = get_post_format();

	$valid_justify_post_1 = solosshopy_nth_child_post_item( 7, 2 );
	$valid_justify_post_2 = solosshopy_nth_child_post_item( 7, 3 );

	if ( 'default' !== $layout || is_single() ) {
		$link_class = $args['class_prefix'] . 'fullwidth';
	}

	if ( ! in_array( $layout, array( 'default', 'default-modern' ) ) ) {
		$size = $args['small'];
	}

	if ( 'default-modern' === $layout || ( 'default' === $layout && 'gallery' === $format ) || is_single() ) {
		$size = $args['fullwidth'];
	}

	if ( 'masonry' === $layout && 'gallery' !== $format ) {
		$size = $args['masonry'];
	}

	if ( 'vertical-justify' === $layout && ! wp_is_mobile() && ( in_array( ( $wp_query->current_post + 1 ), $valid_justify_post_1 ) || in_array( ( $wp_query->current_post + 1 ), $valid_justify_post_2 ) ) ) {
		$size = $args['justify'];
	}

	if ( 'grid' === $layout && ! wp_is_mobile() && '2-cols' === $blog_layout_columns ) {
		$size = $args['fullwidth'];
	}

	return array(
		'size'  => $size,
		'class' => $link_class,
	);
}

/**
 * PHP analog css selector :nth-child( $multiplier*n + $addition).
 *
 * @param int $multiplier Multiplier.
 * @param int $addition   Addition.
 *
 * @return array
 */
function solosshopy_nth_child_post_item( $multiplier, $addition ) {
	global $posts_per_page;

	$valid_item = array();

	for ( $n = 0; $n < $posts_per_page; $n ++ ) {

		$result = $multiplier * $n + $addition;

		if ( $result > $posts_per_page ) {
			break;
		}

		$valid_item[] = $result;
	}

	return $valid_item;
}

/**
 * Check color is light or dark.
 *
 * @param string $color Hex color.
 *
 * @return null|string
 */
function solosshopy_hex_color_is_light_or_dark( $color ) {

	if ( false === strpos( $color, '#' ) ) {
		// Not a hex-color
		return null;
	}

	$hex = str_replace( '#', '', $color );

	if ( 3 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else if ( 6 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	} else {
		return false;
	}

	$luminance = ( $r * 0.299 ) + ( $g * 0.587 ) + ( $b * 0.114 );

	$is_light_or_dark = ( $luminance >= 128 ) ? 'light' : 'dark';

 return apply_filters( 'solosshopy_hex_color_is_light_or_dark', $is_light_or_dark, $color ) ;
}

