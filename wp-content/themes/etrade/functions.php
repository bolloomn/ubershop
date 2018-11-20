<?php

/* Load the core theme framework. */
include_once( get_template_directory() . '/pukka/init.php');

/* Load Custom Menu Walker */
include_once locate_template('/pukka/include/pukka_menu.php');

/* Load woocommerce. */
if( function_exists('is_woocommerce') ){
	include_once locate_template('/woocommerce/woocommerce_configuration.php');
}

function pukka_page_menu( $args = array() ) {
	$defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'wp_page_menu_args', $args );

	$menu = '';

	$list_args = $args;

	// Show Home in the menu
	if ( ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __('Home', 'pukka');
		else
			$text = $args['show_home'];
		$class = '';
		if ( is_front_page() && !is_paged() )
			$class = 'class="current_page_item"';
		$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
		// If the front page is a page, add it to the exclude list
		if (get_option('show_on_front') == 'page') {
			if ( !empty( $list_args['exclude'] ) ) {
				$list_args['exclude'] .= ',';
			} else {
				$list_args['exclude'] = '';
			}
			$list_args['exclude'] .= get_option('page_on_front');
		}
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu )
		$menu = '<ul class="' . esc_attr($args['menu_class']) . '">' . $menu . '</ul>';

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}

if ( !function_exists('pukka_woocommerce_image_dimensions') ) :
// Set product thumbnail sizes after theme is activated
add_action('after_switch_theme', 'pukka_woocommerce_image_dimensions');
function pukka_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '220',	// px
		'height'	=> '330',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '350',	// px
		'height'	=> '526',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '90',	// px
		'height'	=> '135',	// px
		'crop'		=> 1 		// true
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}
endif; // if ( !function_exists('pukka_woocommerce_image_dimensions') ) :


if( !isset($content_width) ){
	$content_width = 1280;
}

if ( !function_exists('pukka_modify_content_width') ) :
/**
 * Modifies content width.
 * Just waits after everything gets loaded.
 *
 * @since   Pukka 1.0
 */
add_action('init', 'pukka_modify_content_width');
function pukka_modify_content_width(){
	global $content_width;

	$settings = (object)pukka_theme_style_settings();

	// left sidebar
	$left_sidebar_enabled = apply_filters('pukka_left_sidebar', $settings->left_sidebar);

	// right sidebar
	$right_sidebar_enabled = apply_filters('pukka_right_sidebar', $settings->right_sidebar);

	// adjust $content_width based on sidebar settings
	if( $left_sidebar_enabled ){
		$content_width -= ((int)$settings->sidebar_left_width + (int)$settings->sidebar_margins);
	}

	if( $right_sidebar_enabled ){
		$content_width -= ((int)$settings->sidebar_right_width + (int)$settings->sidebar_margins);
	}
}
endif; // if ( !function_exists('pukka_modify_content_width') ) :

if ( !function_exists('pukka_theme_setup') ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since   Pukka 1.0
 */
function pukka_theme_setup(){

	/*
	 * Make theme available for translation.
	*/
	// if ( !is_textdomain_loaded( 'pukka' ) ){
		load_theme_textdomain('pukka', get_template_directory() . '/languages');
	// }

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) ){
		require_once( $locale_file );
	}

	// Add title tag support (since WP 4.1)
	add_theme_support('title-tag');

	// Enable support for Post Thumbnails
	add_theme_support('post-thumbnails');

	add_image_size('thumb-brick', 500); // masonry thumb
	add_image_size('thumb-post', 1280, 9999, true); // post thumb

	add_image_size('thumb-cat-col', 570, 590, true); // column category thumb

	// Register menu location
	register_nav_menu('primary', __('Primary Menu', 'pukka'));
	register_nav_menu('secondary', __('Secondary Menu', 'pukka'));
	register_nav_menu('footer', __('Footer Menu', 'pukka'));

	// Add default posts and comments RSS feed links to head
	add_theme_support('automatic-feed-links');

	// Add post formats
	add_theme_support('post-formats', array('video', 'audio', 'gallery', 'link', 'quote'));

	// Custom background support
	add_theme_support('custom-background', array('default-color' => 'e5e0e5'));

	// disable default wp gallery styling
	add_filter( 'use_default_gallery_style', '__return_false' );


	add_theme_support( 'woocommerce' );

	// woo 3.0.x gallery
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action('after_setup_theme', 'pukka_theme_setup');
endif; // if( function_exists('pukka_theme_setup' )


/**
* Standard wp_title stuff
*/
function pukka_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'pukka' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'pukka_wp_title', 10, 2 );

if ( !function_exists('pukka_scripts') ) :
function pukka_scripts(){

	// font awesome
	wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css');

	// Main stylesheet
	wp_enqueue_style('pukka-style', get_stylesheet_uri(), array('font-awesome'));

	// Theme options generated style
	$uploads_dir = wp_upload_dir();
	if(file_exists($uploads_dir['basedir'] . '/theme_style.css')){
		wp_enqueue_style('pukka-theme-style', $uploads_dir['baseurl'] . '/theme_style.css', array('pukka-style'));
	}

	// Fonts
	wp_enqueue_style('google-roboto-font', '//fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext,cyrillic');
	wp_enqueue_style('google=oswald-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext');

	// Needed for shop/blog 'grid'
	wp_enqueue_script('jquery-masonry');

	// Adds JavaScript to pages with the comment form to support sites with
	// threaded comments (when in use).
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Lightbox
	wp_enqueue_script('jquery.swipebox', get_template_directory_uri() . '/js/swipebox/jquery.swipebox.js', array('jquery'));
	wp_enqueue_style('swipebox-style', get_template_directory_uri() . '/js/swipebox/swipebox.css');

	// Slider (testimonials)
	wp_enqueue_script('jquery.flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'));

	// jQuery easing
	wp_register_script('jquery-easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery'));
	wp_register_script('jquery-easing-compatibility', get_template_directory_uri() . '/js/jquery.easing.compatibility.js', array('jquery', 'jquery-easing'));

	// Main theme's JS
	wp_register_script('pukka-script', get_template_directory_uri() . '/js/pukka.js', array('jquery', 'jquery-ui-autocomplete', 'jquery-easing'));
	wp_enqueue_script('pukka-script');

	// Modernizr
	wp_enqueue_script('modernizr', get_template_directory_uri() .'/js/modernizr.custom.js');

	// get all data necessary for displaying grid on category, tag and date archive pages

	global $pukka_js;

	$pukka_js['ajaxurl'] = admin_url('admin-ajax.php');

	wp_localize_script('pukka-script', 'Pukka', $pukka_js);
}
add_action('wp_enqueue_scripts', 'pukka_scripts', 0);
endif; // if( function_exists('pukka_scripts' )



if ( !function_exists('pukka_widgets_init') ) :
/**
 * Registers two widget areas.
 *
 * @since Pukka 1.0
 *
 * @return void
 */
function pukka_widgets_init() {


		register_sidebar( array(
				'name'          => __( 'Home Page Widget Area 1', 'pukka' ),
				'id'            => 'sidebar-homepage-1',
				'description'   => __( 'Appears on homepage.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-homepage-1 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
				'name'          => __( 'Home Page Widget Area 2', 'pukka' ),
				'id'            => 'sidebar-homepage-2',
				'description'   => __( 'Appears on homepage.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-homepage-2 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
				'name'          => __( 'Home Page Widget Area 3', 'pukka' ),
				'id'            => 'sidebar-homepage-3',
				'description'   => __( 'Appears on homepage.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-homepage-3 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );


		register_sidebar( array(
				'name'          => __( 'Left Sidebar Widget Area 1', 'pukka' ),
				'id'            => 'sidebar-left-1',
				'description'   => __( 'Appears on posts and pages in the sidebar.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-left-1 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
				'name'          => __( 'Left Sidebar Widget Area 2', 'pukka' ),
				'id'            => 'sidebar-left-2',
				'description'   => __( 'Appears on posts and pages in the sidebar.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-left-2 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );


		register_sidebar( array(
				'name'          => __( 'Right Sidebar Widget Area 1', 'pukka' ),
				'id'            => 'sidebar-right-1',
				'description'   => __( 'Appears on posts and pages in the sidebar.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-right-1 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
				'name'          => __( 'Right Sidebar Widget Area 2', 'pukka' ),
				'id'            => 'sidebar-right-2',
				'description'   => __( 'Appears on posts and pages in the sidebar.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-right-2 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
				'name'          => __( 'Footer Widget Area 1', 'pukka' ),
				'id'            => 'sidebar-footer-1',
				'description'   => __( 'Appears on posts and pages in the footer.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-footer-1 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
				'name'          => __( 'Footer Widget Area 2', 'pukka' ),
				'id'            => 'sidebar-footer-2',
				'description'   => __( 'Appears on posts and pages in the footer.', 'pukka' ),
				'before_widget' => '<aside id="%1$s" class="widget sidebar-footer-2 %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Shop Left Widget Area', 'pukka' ),
			'id'            => 'sidebar-shop-left-1',
			'description'   => __( 'Appears in your shop area.', 'pukka' ),
			'before_widget' => '<aside id="%1$s" class="widget sidebar-shop-left-1 %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Shop Right Widget Area', 'pukka' ),
			'id'            => 'sidebar-shop-right-1',
			'description'   => __( 'Appears in your shop area.', 'pukka' ),
			'before_widget' => '<aside id="%1$s" class="widget sidebar-shop-right-1 %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );


		// register custom widget areas
		$custom_widget_areas = pukka_get_option('custom_widget_areas');
		if( !empty($custom_widget_areas) ){
			//$cnt = 1;

			foreach( (array)$custom_widget_areas as $widget_area_title ){
				$slug = pukka_slugify($widget_area_title);
				register_sidebar( array(
					'name'          => $widget_area_title,
					'id'            => $slug,
					'description'   => __( 'Custom widget area', 'pukka' ),
					'before_widget' => '<aside id="%1$s" class="widget ' . $slug . ' %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				) );

				//$cnt++;
			}

		}
}
add_action( 'widgets_init', 'pukka_widgets_init' );
endif; // if( function_exists('pukka_widgets_init' )

/**
* Admin bar link
*
*/
add_action( 'admin_bar_menu', 'pukka_toolbar', 999 );
function pukka_toolbar( $wp_admin_bar ) {

	//Parent node
	$wp_admin_bar->add_node(
		array(
			'id'    => 'pukka_theme_settings',
			'title' => __( 'Theme Settings', 'pukka' ),
			'href'  => admin_url( 'themes.php?page=pukka_theme_settings_page' ),
		)
	);
}

/**
 * Prints site logo image if it exists, else site logo as text or site name.
 *
 * @since Pukka 1.0
 *
 * @param int $maxw image max width
 * @param int $maxh image max height
 * @return void
 */
function pukka_logo($maxw = 200){
	$logo = '';
	//normal image logo
	$img_id = trim(pukka_get_option('logo_img_id'));
	//retina image logo
	$img_id_ret = trim(pukka_get_option('retina_logo_img_id'));

	//normal image
	if(!empty($img_id)){
		$logo_img = wp_get_attachment_image_src($img_id, 'full');
		$w = $logo_img[1];
		$h = $logo_img[2];

		// need to check these because of jetpack plugin
		if(!empty($w) && !empty($h)){
			$k = $w / $h; //aspect ratio

			//check if width or height is outside of predefined max width and height
			if($w > $maxw){
				$w = $maxw;
				$h = round($w / $k);
			}
		}else{
			$w = $maxw;
			$h = '';
		}
		$logo = '<img src="'. $logo_img[0] .'" alt="'. get_bloginfo('name') .'" ';

		if( !empty($w) && !empty($h) ){
			$logo .= 'width="'. $w .'" height="'. $h .'" ';
		}

		$logo .= 'class="';
		if(!empty($img_id_ret)) {
			$logo .= 'has-retina';
		}
		$logo .= '" />';

		//retina image
		if(!empty($img_id_ret)){
			$logo_img_ret = wp_get_attachment_image_src($img_id_ret, 'full');
			if(!empty($w) && !empty($h)){
				$w = round($logo_img_ret[1] / 2);
				$h = round($logo_img_ret[2] / 2);
				$k = $w / $h; //aspect ratio

				//check if width or height is outside of predefined max width and height
				if($w > $maxw){
					$w = $maxw;
					$h = round($w / $k);
				}
			}else{
				$w = $maxw;
				$h = '';
			}
			$logo .= '<img src="'. $logo_img_ret[0] .'" alt="'. get_bloginfo('name') .'" width="'. $w .'" height="'. $h .'" class="is-retina" />';
		}
	}
	elseif( '' != trim(pukka_get_option('text_logo')) ){
		$logo = '<span id="logo-text">' . esc_html(stripslashes(pukka_get_option('text_logo'))) . '</span>';
	}//if all else fails, return set blog name as logo
	else{
		$logo = '<span id="logo-text">' . get_bloginfo('name') . '</span>';
	}

	echo $logo;
}


/**
 * Prints links to social media accounts with aprropriate icons
 *
 * @since Pukka 1.0
 */
if( !function_exists('pukka_social_menu') ) :
function pukka_social_menu($echo = true){
	$out = '';

	if( pukka_get_option('facebook_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('facebook_url') . '" target="_blank" class="icon-facebook"><i class="fa fa-facebook"></i></a>';
	endif;

	if( pukka_get_option('twitter_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('twitter_url') . '" target="_blank" class="icon-twitter"><i class="fa fa-twitter"></i></a>';
	endif;

	if( pukka_get_option('youtube_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('youtube_url') . '" target="_blank" class="icon-youtube"><i class="fa fa-youtube"></i></a>';
	endif;

	if( pukka_get_option('soundcloud_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('soundcloud_url') . '" target="_blank" class="icon-soundcloud"><i class="fa fa-cloud"></i></a>';
	endif;

	if( pukka_get_option('gplus_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('gplus_url') . '" target="_blank" class="icon-google"><i class="fa fa-google-plus"></i></a>';
	endif;

	 if( pukka_get_option('vimeo_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('vimeo_url') . '" target="_blank" class="icon-vimeo"><i class="fa fa-vimeo-square"></i></a>';
	 endif;

	 if( pukka_get_option('linkedin_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('linkedin_url') . '" target="_blank" class="icon-linkedin"><i class="fa fa-linkedin"></i></a>';
	 endif;

	 if( pukka_get_option('pinterest_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('pinterest_url') . '" target="_blank" class="icon-pinterest"><i class="fa fa-pinterest"></i></a>';
	 endif;

	 if( pukka_get_option('instagram_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('instagram_url') . '" target="_blank" class="icon-instagram"><i class="fa fa-instagram"></i></a>';
	 endif;

	 if( pukka_get_option('tumblr_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('tumblr_url') . '" target="_blank" class="icon-tumblr"><i class="fa fa-tumblr"></i></a>';
	 endif;

	 if( pukka_get_option('flickr_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('flickr_url') . '" target="_blank" class="icon-flickr"><i class="fa fa-flickr"></i></a>';
	 endif;

	 if( pukka_get_option('dribbble_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('dribbble_url') .'" target="_blank" class="icon-dribbble"><i class="fa fa-dribble"></i></a>';
	 endif;

	 if( pukka_get_option('rss_url') != '' ) :
		$out .= '<a href="' . pukka_get_option('rss_url') . '" target="_blank" class="icon-rss"><i class="fa fa-rss"></i></a>';
	 endif;

	if( pukka_get_option('custom_social_links') != '' ){
		$out .= pukka_get_option('custom_social_links');
	}

	if($echo){
		echo $out;
	}else{
		return $out;
	}
}
endif; // if( !function_exists('pukka_social_menu') )



if ( ! function_exists( 'pukka_paging_nav' ) ) :
/**
 * Displays navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function pukka_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<!--<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'pukka' ); ?></h1>-->
		<div class="nav-links clearfix">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'pukka' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'pukka' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;



if( !function_exists('pukka_entry_meta') ) :
/**
 * Prints post meta (such as date, category, author etc.)
 *
 * @since Pukka 1.0
 *
 */
function pukka_entry_meta($args=array()){

		$hide_date = (!empty($args['hide_date']) && $args['hide_date'] == true);

		$hide_author = (!empty($args['hide_author']) && $args['hide_author'] == true)
						|| ('on' == pukka_get_option('single_author_site') && 'post' == get_post_type());

		$hide_categories = (!empty($args['hide_categories']) && $args['hide_categories'] == true);

		$hide_tags = (!empty($args['hide_tags']) && $args['hide_tags'] == true);

		// Date
		if( !$hide_date ){

			if( !is_single() ){
				$date = sprintf( '<span class="entry-date updated buttons"><a href="%1$s" title="%2$s" rel="bookmark"><time datetime="%3$s"><span class="day">%4$s</span><span class="month">%5$s</span></time></a></span>',
						esc_url( get_permalink() ),
						esc_attr( sprintf( __( 'Permalink to %s', 'pukka' ), the_title_attribute( 'echo=0' ) ) ),
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date('d') ),
						esc_html( get_the_date('M') )
				);
			}
			else{
				// link omitted
				$date = $date = sprintf( '<span class="entry-date updated buttons"><time datetime="%1$s"><span class="day">%2$s</span><span class="month">%3$s</span></time></span>',
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date('d') ),
						esc_html( get_the_date('M') )
				);
			}

			echo $date;
		}

		// Post author
		if ( !$hide_author ) {
				printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						esc_attr( sprintf( __( 'View all posts by %s', 'pukka' ), get_the_author() ) ),
						get_the_author()
				);
		}

		// Categories
		if( !$hide_categories ){
			$categories_list = get_the_category_list( __( ', ', 'pukka' ) );
			if ( $categories_list ) {
					echo '<span class="categories-links">' . $categories_list . '</span>';
			}
		}

		if( !$hide_tags ){
			$tag_list = get_the_tag_list( '', __( ', ', 'pukka' ) );
			if ( $tag_list ) {
					echo '<span class="tags-links">' . $tag_list . '</span>';
			}
		}
}
endif; // if( !function_exists('pukka_entry_meta') ) :

if( !function_exists('pukka_has_post_media') ) :
/**
 * Checks if post has media (video, audio).
 *
 * @since Pukka 1.0
 *
 * @param int $post_id Post ID
 * @return bool
 *
 */
function pukka_has_post_media($post_id=0){

	if( !$post_id ){
		$post_id = get_the_ID();
	}

	return (get_post_meta($post_id, PUKKA_POSTMETA_PREFIX .'media_url', true) != '' || get_post_meta($post_id, PUKKA_POSTMETA_PREFIX .'media_embed', true) != '');
}
endif; // if( !function_exists('pukka_media') ) :

if( !function_exists('pukka_media') ) :
/**
 * Generate embed code for post media.
 *
 * @since Pukka 1.0
 *
 * @uses pukka_get_embeded_media()
 *
 */
function pukka_media(){
	global $post, $content_width;
	$width = $content_width;
	$height = 390;
	/*
	if( has_post_format('audio') ){
		$height = 165; // soundcloud
	}
	else{
		$height = 390;
	}
	*/
	if( get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'media_url', true) != '' || get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'media_embed', true) != ''){
		if( get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'media_url', true) != '' ){
			echo pukka_get_embeded_media(get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'media_url', true), $width, $height);
		}
		else{
			echo get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'media_embed', true);
		}
	 }
}
endif; // if( !function_exists('pukka_media') ) :

/**
 * Gets embed code for media
 *
 * @since Pukka 1.0
 *
 * @uses wp_oembed_get()
 *
 * @param string $media_url The format of URL that this provider can handle.
 * @param string $width Embedded media width.
 * @param boolean $height Embedded media height.
 */
function pukka_get_embeded_media($media_url, $width=1280, $height=390){

		$args = array(
					'width'=> $width,
					//'height' => $height, //it looks like width will be downsized in aspect to height value
				);

		return wp_oembed_get($media_url, $args);
}


if( !function_exists('pukka_get_excerpt') ) :
/**
 * Creates excerpt with custom read more arrow every time the_excerpt is called
 *
 * @since Pukka 1.0
 *
 * @param string $excerpt Excerpt text
 * @return string
 */
function pukka_get_excerpt($excerpt) {
	global $post;

	// dont print 'read more' if excerpt is empty
	if( empty($excerpt) ){
		return;
	}

	if( has_post_format('link') ){
		$url = get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'link', true);
		$target = '_blank';
	}
	else{
		$url = get_permalink($post->ID);
		$target = '_self';
	}

	// $read_more = pukka_get_option('read_more') != '' ? pukka_get_option('read_more') : '&rarr;';
	$read_more = __('Read more', 'pukka');

	if( !has_post_format('quote') ){
		return $excerpt . '<a class="more-link" href="'. $url . '" title="' . __('Read more', 'pukka') . '" target="'. $target .'"> '. $read_more .' </a>';
	}
	else{
		return $excerpt;
	}
}
add_filter('get_the_excerpt', 'pukka_get_excerpt', 998);
endif; // if( !function_exists('pukka_get_excerpt') ) :

/**
* Attach lightbox to post galleries
*
*/
function pukka_gallery_swipebox($content){

	// add checks if you want to add prettyPhoto on certain places (archives etc).

	return str_replace("<a", "<a class='swipebox'", $content);
}
add_filter('wp_get_attachment_link', 'pukka_gallery_swipebox');


if( !function_exists('pukka_attach_swipebox') ) :
/**
*  Attach lightbox to single image
* (or arbitrary content which has only links to images)
*
* @param string $content Content with links to images
* @param string $gallary_id Something unique so multiple galleries on the same page don't get mixed
*
*/
function pukka_attach_swipebox($content, $gallery_id=false){
	$rel_value = 'gallery';

	if( $gallery_id ){
		$rel_value .= '-'. $gallery_id;
	}

	return str_replace('<a', '<a class="swipebox" rel="'. $rel_value .'"', $content);
}
add_filter('pukka_attach_lightbox', 'pukka_attach_swipebox', 1, 2);
endif; // if( !function_exists('pukka_attach_swipebox') ) :

/**
 * Highlights page, in a backend 'Pages' screen,
 * which is set to be used as a site's Front page.
 *
 * @since Pukka 1.0
 */
add_action('admin_head', 'pukka_page_placeholder');
function pukka_page_placeholder(){
	$front_page_id = get_option('page_on_front');
	if( !empty($front_page_id) ){
		echo '<style>#post-'.$front_page_id.' { background-color: #FFFFCC; } #post-'.$front_page_id.' .post-title strong:after { color: #999999; content: "'.__('(This page is a placeholder for front page)', 'pukka').'"; font-size: 11px; font-style: italic; font-weight: normal; text-decoration: none; margin-left: 10px;</style>';
	}
}


/**
* Custom Menu Nav Walker, used for adding columns and image to menus
*/
class PukkaNavDropdown extends Walker_Nav_Menu
{
	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if( 0 == $depth ){
			$class_names = 'menu-dropdown';
		}
		else{
			$class_names = 'menu-column-links';
		}

		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<div class=".$class_names."><ul>\n";
	}

	function end_lvl( &$output, $depth = 1, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul></div>\n";
	}

	/*
	function start_el ( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	global $wp_query;
	$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	$class_names = $value = '';

	$classes = empty( $item->classes ) ? array() : (array) $item->classes;
	$classes[] = 'menu-item-' . $item->ID;

	$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
	$class_names = ' class="' . esc_attr( $class_names ) . '"';

	$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
	$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

	$output .= $indent . '<li' . $id . $value . $class_names .'>';

	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

	// First level item ?
	if ( $depth == 0 ) {
		// If so add custom class
		$attributes .= ' class="nav-top-link"';
	}

	$description = '';
	if(strpos($class_names,'image-column') !== false){
		$description = '<img src="'.$item->description.'" alt=" "/>';
	}

	// quick fix for: https://core.trac.wordpress.org/ticket/18232
	if( is_array($args) ){
		$args = (object)$args;
	}

	$item_output = $args->before;
	$item_output .= '<a'. $attributes .'>';
	$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
	$item_output .= $description; // <img /> or empty
	$item_output .= '</a>';
	$item_output .= $args->after;

	$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
*/
}


// Shop menu

function pukka_shop_menu(){
	// if woocommerce not active, do nothing
	if(!function_exists('is_woocommerce')) return;
?>
<div id="shop-menu" class="basic">
	<ul>
		<?php
		global $woocommerce;
		if(!empty($woocommerce)) {

		$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
		$myaccount_page_url = $myaccount_page_id != '' ? get_permalink( $myaccount_page_id ) : '';
		?>
		<li id="pukka-login">
			<a href="<?php echo $myaccount_page_url; ?>">
				<?php echo ( is_user_logged_in() ) ? __('My Account', 'pukka') : __('Login', 'pukka'); ?>
			</a>
		</li>

		<li class="pukka-cart"><a href="<?php echo get_permalink(wc_get_page_id( 'cart' )); ?>"><?php echo sprintf(_n('<span class="txt-dk">%d</span> item', '<span class="txt-dk">%d</span> items', $woocommerce->cart->cart_contents_count, 'pukka'), $woocommerce->cart->cart_contents_count);?></a></li>
		<li class="pukka-cart-value txt-dk"><a href="<?php echo get_permalink(wc_get_page_id( 'cart' )); ?>"><?php echo $woocommerce->cart->get_cart_total(); ?></a></li>
		<li class="pukka-cart-content">
			<a href="<?php echo get_permalink(wc_get_page_id( 'cart' )); ?>">
				<div id="menu-basket" class="buttons"><i class="fa fa-shopping-cart"></i></div>
			</a>
			<div class="basket basic">
				<div class="cart-subtotal"><?php _e('No items in cart', 'pukka'); ?></div>
			</div>
		</li>
		<?php } ?>
		<li class="pukka-cart-search"><div id="menu-search" class="fa fa-search">&nbsp;</div></li>
	</ul>
</div><!-- #shop-menu -->
<?php
}

/**
 * Custom Comment Walker class for printing comments
 *
 * @since Pukka 1.0
 *
 */
class Pukka_Walker_Comment extends Walker_Comment {

	protected function comment( $comment, $depth, $args ) {
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
		<?php endif; ?>
		<div id="comment-<?php comment_ID(); ?>" class="comment_container">

			<?php echo ( $args['avatar_size'] != 0 ? get_avatar( $comment, $args['avatar_size'] ) :'' ); ?>

			<div class="comment-text">

				<?php if ( $comment->comment_approved == '0' ) : ?>

					<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'pukka' ); ?></em></p>

				<?php else : ?>

					<p class="meta clearfix">
						<strong itemprop="author"><?php comment_author(); ?></strong> <time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( __( get_option( 'date_format' ), 'woocommerce' ) ); ?></time>
					</p>

				<?php endif; ?>

				<div itemprop="description" class="description"><?php comment_text(); ?></div>
			</div>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif;

	}

	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			call_user_func( $args['callback'], $comment, $args, $depth );
			return;
		}

		if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
			$this->ping( $comment, $depth, $args );
		} elseif ( 'html5' === $args['format'] ) {
			$this->html5_comment( $comment, $depth, $args );
		} else {
			$this->comment( $comment, $depth, $args );
		}
	}

}

/**
 * Setup for custom comment form.
 *
 * @since Pukka 1.0
 *
 *
 * @param array $args
 * @return string $args
 */
function pukka_comments($args){
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$user = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';
		$required_text = sprintf(' ' . __('Required fields are marked %s', 'pukka'), '<span class="required">*</span>');

		$args = array(
			'id_form'           => 'commentform',
			'id_submit'         => 'submit',
			'class_submit'      => 'submit',
			'name_submit'       => 'submit',
			'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
			'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
			'title_reply'       => __('Leave a Comment', 'pukka'),
			'title_reply_to'    => __('Leave a Reply to %s', 'pukka'),
			'cancel_reply_link' => __('Cancel Reply', 'pukka'),
			'label_submit'      => __('Post Comment', 'pukka'),

			'comment_field' =>  '<p class="comment-form-comment">' .
				'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'. __('Your text', 'pukka') .'">' .
				'</textarea></p>',

			'must_log_in' => '<p class="must-log-in">' .
				sprintf(
					__( 'You must be <a href="%s">logged in</a> to post a comment.' ),
					wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
				) . '</p>',

			'logged_in_as' => '<p class="logged-in-as">' .
				sprintf(
				__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'pukka' ),
					admin_url( 'profile.php' ),
					$user_identity,
					wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
				) . '</p>',

			'comment_notes_before' => '<p class="comment-notes">' .
				__( 'Your email address will not be published.', 'pukka') . ( $req ? $required_text : '' ) .
				'</p>',
				/*
			'comment_notes_after' => '<p class="form-allowed-tags">' .
				sprintf(
					__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ),
					' <code>' . allowed_tags() . '</code>'
				) . '</p>',
			*/
			'comment_notes_after' => '',
			'fields' => apply_filters( 'comment_form_default_fields', array(

				'author' =>
					'<p><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
					'" placeholder="'. __( 'Name', 'pukka' ) . ( $req ? ' *' : '' ) .
					'" size="30"' . $aria_req . ' /></p>',

				'email' =>
					'<p><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
					'" placeholder="'. __( 'Email', 'pukka' ) . ( $req ? ' *' : '' ) .
					'" size="30"' . $aria_req . ' /></p>',

				'url' =>
					'<p><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
					'" placeholder="'.  __( 'Website', 'pukka' ) .
					'" size="30" /></p>'
				)
			),
		);


	return $args;
}
add_filter('comment_form_defaults', 'pukka_comments');


/**
 * Load the TGM Plugin Activator class to notify the user
 * to install the Envato WordPress Toolkit Plugin
 */
require_once( get_template_directory() . '/inc/class-tgm-plugin-activation.php' );

function tgmpa_register_toolkit() {

	$plugins = array(

		// Envato Market plugin
		array(
			'name' => 'Envato Market', // The plugin name
			'slug' => 'envato-market', // The plugin slug (typically the folder name)
			'source' => get_template_directory() . '/inc/plugins/envato-market.zip', // The plugin source
			'required' => false, // If false, the plugin is only 'recommended' instead of required
			'version' => '2.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' => '', // If set, overrides default API URL and points to an external URL
		),

		// Custom posts plugin
		array(
			'name' => 'Pukka Custom Posts', // The plugin name
			'slug' => 'pukka-custom-posts', // The plugin slug (typically the folder name)
			'source' => get_template_directory() . '/inc/plugins/pukka-custom-posts.zip', // The plugin source
			'required' => false, // If false, the plugin is only 'recommended' instead of required
			'version' => '1.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' => '', // If set, overrides default API URL and points to an external URL
		),

		// WooCommerce
		array(
			'name'     				=> 'WooCommerce', // The plugin name
			'slug'     				=> 'woocommerce', // The plugin slug (typically the folder name)
//			'source'   				=> get_template_directory() . '/inc/plugins/woocommerce.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.2.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

		// Revolution slider
		array(
			'name'     				=> 'Revolution slider', // The plugin name
			'slug'     				=> 'revslider', // The plugin slug (typically the folder name)
			'source'   				=> get_template_directory() . '/inc/plugins/revslider.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '4.5.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

		// Ninja forms
		array(
			'name'     				=> 'Ninja Forms', // The plugin name
			'slug'     				=> 'ninja-forms', // The plugin slug (typically the folder name)
//			'source'   				=> get_template_directory() . '/inc/plugins/ninja-forms.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.6.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
	);

	// i18n text domain used for translation purposes
	$theme_text_domain = 'pukka';

	// Configuration of TGM
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		'strings'          => array(
			'page_title'                      => __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                      => __( 'Install Plugins', $theme_text_domain ),
			'installing'                      => __( 'Installing Plugin: %s', $theme_text_domain ),
			'oops'                            => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                => __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', $theme_text_domain ),
			'nag_type'                        => 'updated'
		)
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'tgmpa_register_toolkit' );

if( !function_exists('pukka_get_object_term') ) :
/**
* Returns first object term
*
* @param mixed $object_ids
* @param mixed $taxonomies
*
* @return mixed object/bool
*/
function pukka_get_object_term($object_ids, $taxonomies){

	if( empty($object_ids) || empty($taxonomies) ){
		return false;
	}

	$terms = wp_get_object_terms( $object_ids, $taxonomies );

	if( !empty($terms[0]) && !is_wp_error($terms) ){
		return $terms[0];
	}
	else{
		return false;
	}
}
endif; // if( !function_exists('pukka_get_object_term') ) :


/**
 * Util functions primarly used for displaying demo content.
 * But they are nice example of applying filters and overriding theme settings from template files.
 */

if( !function_exists('pukka_posts_column') ) :
/* Column number filter */
add_filter('pukka_category_column_no', 'pukka_posts_column');
function pukka_posts_column($col_no=1){

	if( !is_category() || empty($_GET['col_no']) ){
		return $col_no;
	}
	else{
		return $_GET['col_no'];
	}
}
endif; // if( !function_exists('pukka_posts_column') ) :


if( !function_exists('pukka_right_sidebar') ) :
/* Right sidebar filter */
add_filter('pukka_right_sidebar', 'pukka_right_sidebar');
function pukka_right_sidebar($sidebar_enabled){

	if( !empty($_GET['sidebars']) ){
		$sidebars = explode(',', $_GET['sidebars']);

		return in_array('right', $sidebars);
	}
	else{
		return $sidebar_enabled;
	}
}
endif; // if( !function_exists('pukka_right_sidebar') ) :


if( !function_exists('pukka_left_sidebar') ) :
/* Left sidebar filter */
add_filter('pukka_left_sidebar', 'pukka_left_sidebar');
function pukka_left_sidebar($sidebar_enabled){
	if( !empty($_GET['sidebars']) ){
		$sidebars = explode(',', $_GET['sidebars']);

		return in_array('left', $sidebars);
	}
	else{
		return $sidebar_enabled;
	}
}
endif; // if( !function_exists('pukka_right_sidebar') ) :

if( !function_exists('pukka_page_width') ) :
/* Page width filter */
add_filter('pukka_page_width', 'pukka_page_width');
function pukka_page_width($page_width){

	if( !empty($_GET['page_width']) ){
		return $_GET['page_width'];
	}
	else{
		return $page_width;
	}
}
endif; // if( !function_exists('pukka_page_width') ) :


if( !function_exists('pukka_primary_footer') ) :
/* Primary footer filter */
add_filter('pukka_primary_footer', 'pukka_primary_footer');
function pukka_primary_footer($footer_enabled){

	if( !empty($_GET['footers']) ){
		$footers = explode(',', $_GET['footers']);

		return in_array('primary', $footers);
	}
	else{
		return $footer_enabled;
	}
}
endif; // if( !function_exists('pukka_primary_footer') ) :


if( !function_exists('pukka_secondary_footer') ) :
/* Primary footer filter */
add_filter('pukka_secondary_footer', 'pukka_secondary_footer');
function pukka_secondary_footer($footer_enabled){

	if( !empty($_GET['footers']) ){
		$footers = explode(',', $_GET['footers']);

		return in_array('secondary', $footers);
	}
	else{
		return $footer_enabled;
	}
}
endif; // if( !function_exists('pukka_secondary_footer') ) :

if( !function_exists('pukka_footer_menu') ) :
/* Primary footer filter */
add_filter('pukka_footer_menu', 'pukka_footer_menu');
function pukka_footer_menu($footer_enabled){

	if( !empty($_GET['footers']) ){
		$footers = explode(',', $_GET['footers']);

		return in_array('footer-menu', $footers);
	}
	else{
		return $footer_enabled;
	}
}
endif; // if( !function_exists('pukka_footer_menu') ) :

if( !function_exists('pukka_top_menu') ) :
/* Primary footer filter */
add_filter('pukka_secondary_menu', 'pukka_secondary_menu');
function pukka_secondary_menu($header_enabled){

	if( !empty($_GET['headers']) ){
		$headers = explode(',', $_GET['headers']);

		return in_array('sedcondary-menu', $headers);
	}
	else{
		return $header_enabled;
	}
}
endif; // if( !function_exists('pukka_secondary_menu') ) :

if( !function_exists('pukka_post_author') ) :

function pukka_post_author(){
		global $post;

		$out = '';
		$author_id = get_the_author_meta('ID');
		$name = get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name');
		if(empty($name)){
			$name = get_the_author_meta('display_name');
		}
		$email = get_the_author_meta('user_email');
		$website = get_the_author_meta('user_url');
		$author_url = get_author_posts_url( $author_id);
		$social = get_the_author_meta('twitter');
		$avatar =  get_avatar($author_id);
		$description = get_the_author_meta('description');

		$out .= '<div class="author-meta clearfix">';

		$out .= '<div class="author-meta-avatar">';
		$out .= $avatar;
		$out .= '<div class="author-links-wrap">';

		if( !empty($social) ){
			$out .= '<div class="author-links"><a href="' . $social . '" target="_blank"><i class="fa fa-twitter"></i></a></div>';
		}

		if( !empty($email) ){
			$out .= '<div class="author-links"><a href="mailto:' . $email . '"><i class="fa fa-envelope-o"></i></a></div>';
		}

		if( !empty($website) ){
			$out .= '<div class="author-links"><a href="' . $website . '"><i class="fa fa-home"></i></a></div>';
		}

		$out .= '</div><!-- .author-links-wrap -->';
		$out .= '</div><!-- .author-meta-avatar -->';

		$out .= '<div class="author-description">';
		$out .= '<div class="author-name"><a href="' . $author_url . '">' . $name . '</a></div>';
		$out .= '<div class="description-text">' . $description . '</div>';
		$out .= '</div><!-- .author-description -->';

		$out .= '</div><!-- .author-meta -->';

		return $out;
	}

endif;



/* Add user contact info */
function pukka_add_contact_methods($profile_fields) {

	// Add new fields
	$profile_fields['twitter'] = __('Twitter URL', 'pukka');
	// $profile_fields['facebook'] = 'Facebook URL';
	// $profile_fields['gplus'] = 'Google+ URL';

	return $profile_fields;
}
add_filter('user_contactmethods', 'pukka_add_contact_methods');
