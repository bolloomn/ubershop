<?php
/**
 * TM-Wizard configuration.
 *
 * @var array
 *
 * @package Solosshopy
 */

$plugins = array(
	'cherry-data-importer' => array(
		'name'   => esc_html__( 'Cherry Data Importer', 'solosshopy' ),
		'source' => 'remote', // 'local', 'remote', 'wordpress' (default).
		'path'   => 'https://github.com/CherryFramework/cherry-data-importer/archive/master.zip',
		'access' => 'skins',
	),
	'elementor' => array(
		'name'   => esc_html__( 'Elementor Page Builder', 'solosshopy' ),
		'access' => 'skins',
	),
	'jet-elements' => array(
		'name'   => esc_html__( 'Jet Elements addon For Elementor', 'solosshopy' ),
		'source' => 'local',
		'path'   => SOLOSSHOPY_THEME_DIR . '/assets/includes/plugins/jet-elements.zip',
		'access' => 'skins',
	),
	'cherry-popups' => array(
		'name'   => esc_html__( 'Cherry PopUps', 'solosshopy' ),
		'access' => 'skins',
	),
	'cherry-sidebars' => array(
		'name'   => esc_html__( 'Cherry Sidebars', 'solosshopy' ),
		'access' => 'skins',
	),
	'woocommerce' => array(
		'name'   => esc_html__( 'Woocommerce', 'solosshopy' ),
		'access' => 'skins',
	),
	'cherry-socialize' => array(
		'name'   => esc_html__( 'Cherry Socialize', 'solosshopy' ),
		'access' => 'skins',
	),
	'tm-woocommerce-ajax-filters' => array(
		'name'   => esc_html__( 'TM Woocommerce Ajax Filters', 'solosshopy' ),
		'source' => 'remote',
		'path' => 'https://github.com/ZemezLab/tm-woocommerce-ajax-filters/archive/master.zip',
		'access' => 'skins',
	),
	'tm-woocommerce-compare-wishlist' => array(
		'name'   => esc_html__( 'TM Woocommerce Compare Wishlist', 'solosshopy' ),
		'access' => 'skins',
	),
	'tm-woocommerce-package' => array(
		'name'   => esc_html__( 'TM Woocommerce Package', 'solosshopy' ),
		'access' => 'skins',
	),
	'woocommerce-social-media-share-buttons' => array(
		'name'   => esc_html__( 'Woocommerce Social Media Share Buttons', 'solosshopy' ),
		'access' => 'skins',
	),
	'tm-woocommerce-quick-view' => array(
		'name'   => esc_html__( 'TM WooCommerce Quick View', 'solosshopy' ),
		'source' => 'local',
		'path'   => SOLOSSHOPY_THEME_DIR . '/assets/includes/plugins/tm-woocommerce-quick-view.zip',
		'access' => 'skins',
	),
	'wordpress-social-login' => array(
		'name'   => esc_html__( 'WordPress Social Login', 'solosshopy' ),
		'access' => 'skins',
	),
    'smart-slider-3' => array(
        'name'   => esc_html__( 'Smart Slider 3', 'solosshopy' ),
        'access' => 'skins',
    ),
	'cherry-team-members' => array(
		'name'   => esc_html__( 'Cherry Team Members', 'solosshopy' ),
		'access' => 'skins',
	),
	'shop-feed-for-instagram-by-snapppt' => array(
		'name'   => esc_html__( 'Shop Feed for Instagram by Snapppt', 'solosshopy' ),
		'access' => 'skins',
	),
	'woocommerce-currency-switcher' => array(
		'name'   => esc_html__( 'WooCommerce Currency Switcher', 'solosshopy' ),
		'access' => 'skins',
	),
	'tm-mega-menu' => array(
		'name'   => esc_html__( 'TM Mega Menu', 'solosshopy' ),
		'source' => 'local',
		'path'   => SOLOSSHOPY_THEME_DIR . '/assets/includes/plugins/tm-mega-menu.zip',
		'access' => 'skins',
	),
	'contact-form-7' => array(
		'name'   => esc_html__( 'Contact Form 7', 'solosshopy' ),
		'access' => 'skins',
	),
	'shortcode-widget' => array(
		'name'   => esc_html__( 'Shortcode Widget', 'solosshopy' ),
		'access' => 'skins',
	),
	'cherry-search' => array(
		'name'   => esc_html__( 'Cherry Search', 'solosshopy' ),
		'access' => 'skins',
	),
);

/**
 * Skins configuration.
 *
 * @var array
 */
$skins = array(
	'advanced' => array(
		'default' => array(
			'full'  => array(
				'cherry-data-importer',
				'elementor',
				'jet-elements',
				'cherry-popups',
				'cherry-sidebars',
				'cherry-socialize',
				'woocommerce',
				'tm-woocommerce-ajax-filters',
				'tm-woocommerce-compare-wishlist',
				'tm-woocommerce-package',
				'woocommerce-social-media-share-buttons',
				'tm-woocommerce-quick-view',
				'wordpress-social-login',
                'smart-slider-3',
				'cherry-team-members',
				'shop-feed-for-instagram-by-snapppt',
                'woocommerce-currency-switcher',
				'tm-mega-menu',
				'contact-form-7',
				'shortcode-widget',
				'cherry-search',
			),
			'lite'  => false,
			'demo'  => 'http://ld-wp.template-help.com/tf/solosshopy_v2',
			'thumb' => get_template_directory_uri() . '/assets/demo-content/default/default-thumb.png',
			'name'  => esc_html__( 'Solosshopy', 'solosshopy' ),
		),
	),
);

$texts = array(
	'theme-name' => esc_html__( 'Solosshopy', 'solosshopy' ),
);
