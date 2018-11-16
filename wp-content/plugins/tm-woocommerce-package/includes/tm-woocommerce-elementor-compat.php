<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'TM_WooCommerce_Elementor_Compat' ) ) {

	/**
	 * Define TM_WooCommerce_Elementor_Compat class
	 */
	class TM_WooCommerce_Elementor_Compat {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		function __construct() {
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'edit_scripts' ) );
		}

		/**
		 * Register widgets assets in editor
		 *
		 * @return [type] [description]
		 */
		public function edit_scripts() {

			tm_wc()->register_admin_assets();

			wp_enqueue_media();

			wp_enqueue_style( 'tm-banners-grid-admin' );
			wp_enqueue_script( 'tm-banners-grid-admin' );

			wp_enqueue_style( 'tm-custom-menu-widget-admin' );
			wp_enqueue_script( 'tm-custom-menu-widget-admin' );

			wp_enqueue_style( 'tm-about-store-widget-admin' );
			wp_enqueue_script( 'tm-about-store-widget-admin' );

			$banners_grids = tm_woo_available_banners_grids();
			$col           = tm_woo_banners_grid_col();

			$translation_array = array(
				'mediaFrameTitle' => __( 'Choose banner image', 'tm-woocommerce-package' ),
				'maxBanners'      => count ( $banners_grids ),
				'setLinkText'     => __( 'Set link', 'tm-woocommerce-package' ),
				'col'             => $col
			);

			wp_localize_script( 'tm-banners-grid-admin', 'bannerGridWidgetAdmin', $translation_array );

			wp_enqueue_script(
				'tm-woo-elementor-editor',
				tm_wc()->plugin_url() . '/assets/js/elementor-compat.js',
				array( 'elementor-editor' ),
				TM_WOOCOMMERCE_VERISON,
				true
			);

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of TM_WooCommerce_Elementor_Compat
 *
 * @return object
 */
function tm_woocommerce_elemntor_compat() {
	return TM_WooCommerce_Elementor_Compat::get_instance();
}
