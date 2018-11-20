<?php
	// CONFIG
	if( !defined('PUKKA_VERSION') ) define('PUKKA_VERSION', '0.7.2');

	define('PUKKA_THEME_NAME', 'UberShop');
	define('PUKKA_THEME_VERSION', '1.1.16');
	define('PUKKA_HOMEPAGE', 'http://pukkathemes.com/');
	define('THEME_DIR', get_template_directory());
	define('THEME_URI', get_template_directory_uri());

	// DIR NAMES
	define('PUKKA_OVERRIDES_DIR_NAME', 'pukka-overrides');
	define('PUKKA_MODULES_DIR_NAME', 'modules');

	define('PUKKA_DIR', THEME_DIR .'/pukka');
	define('PUKKA_URI', THEME_URI .'/pukka');
	if( !defined('PUKKA_FRAMEWORK_DIR') ) define('PUKKA_FRAMEWORK_DIR', get_template_directory() .'/pukka/framework');
	if( !defined('PUKKA_FRAMEWORK_URI') ) define('PUKKA_FRAMEWORK_URI', get_template_directory_uri() .'/pukka/framework');

	if( !defined('PUKKA_OPTIONS_NAME') ) define('PUKKA_OPTIONS_NAME', 'pukka_options');
	define('PUKKA_THEME_COLORSCHEME_NAME', 'pukka_theme_colorscheme');

	// GLOBAL VAR
	global $pukka_js;

	// CONTENT INIT
	if( !defined('PUKKA_POSTMETA_PREFIX') ) define('PUKKA_POSTMETA_PREFIX', '_pukka_');

	// Functions & Hooks
	include_once(PUKKA_DIR .'/ubershop_functions.php');

	// FRAMEWORK INIT
	include_once(PUKKA_FRAMEWORK_DIR . '/pukka_init.php');

	include_once(PUKKA_DIR . '/include/ubershop.theme.class.php');
	include_once(PUKKA_DIR . '/util/init-theme-styles.php');
	include_once(PUKKA_DIR . '/util/init-theme-options.php');

	include_once(PUKKA_DIR . '/util/init-post-meta.php');
	include_once(PUKKA_DIR . '/util/init-page-meta.php');

	// WIDGETS
	include_once PUKKA_DIR .'/widgets/image-widget/image_widget.php';
	include_once PUKKA_DIR .'/widgets/featured-post-widget/featured_post_widget.php';
	include_once PUKKA_DIR .'/widgets/facebook-widget/facebook_widget.php';
	include_once PUKKA_DIR .'/widgets/ad-widget/ad_widget.php';
	include_once PUKKA_DIR .'/widgets/ads-50-widget/ads_50_widget.php';
	include_once PUKKA_DIR .'/widgets/latest-posts-widget/latest_posts_widget.php';
	include_once PUKKA_DIR .'/widgets/fa-widget/fa_widget.php';
	include_once PUKKA_DIR .'/widgets/banner-widget/banner_widget.php';
	include_once PUKKA_DIR .'/widgets/testimonials-widget/testimonials_widget.php';

	// SHORTCODES
	include_once PUKKA_DIR .'/include/shortcodes.php';

	// Init main theme object
	$pukka_theme_option_pages = array();
	$pukka_theme_option_pages = apply_filters('pukka_add_theme_options', $pukka_theme_option_pages);
	$pukka = new UberShopTheme($pukka_theme_option_pages);

	// SOCIAL MEDIA SUPPORT
	$social_media = new PukkaSocialMedia(array('og_desc_length' => 220)); // Social media

	// MODULES
	include_once PUKKA_DIR . '/'. PUKKA_MODULES_DIR_NAME .'/dynamic-meta/init.php';

	// Add meta boxes
	if( class_exists('PukkaMetaBox') ){
		$pukka_meta_boxes = array();

		$pukka_meta_boxes = apply_filters('pukka_add_meta_boxes', $pukka_meta_boxes);
		$meta_box = new PukkaMetaBox($pukka_meta_boxes);
	}
