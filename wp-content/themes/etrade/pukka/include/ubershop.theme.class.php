<?php if ( ! defined('PUKKA_VERSION')) exit('No direct script access allowed');
/*
* Class with features specific to UberGrid theme
*
* Requires framework/php/pukkatheme.class.php
*/


if (!class_exists('UberShopTheme')) :

	class UberShopTheme extends PukkaTheme {

		/**
		 * Class constructor
		 *
		 * @since Pukka 1.0
		 *
		 * @param array $theme_option_pages Theme options pages and options for each page
		 */
		public function __construct($theme_option_pages) {

			parent::__construct($theme_option_pages);

			// frontend scripts
			if(!is_admin()){
				// $this->enqueueThemeScripts();
				add_action('init', array(&$this, 'enqueueThemeScripts'), 10);
			}

			// print custom ad head code
			add_action('wp_head', array(&$this, 'printAdHeadCode'), 10);
		}

		/**
		 * Adding style to page
		 *
		 * @since Pukka 1.0
		 */
		public function enqueueThemeScripts() {
			// $css is protected var in pukka.theme.class.php
			
			/*
			* these two methods are no longer in use because there is a function in ubershop_functions.php
			* which generates same css and saves it to file. If you have problems with writing to uploads
			* folder, you can uncomment next two function calls
			*/
			//$this->css .= $this->customThemeCss();
			//$this->css .= $this->sidebarsCSS();
			
			global $pukka_js;
			
			$settings = (object)pukka_theme_style_settings();
			$pukka_js['product_column_width'] = $settings->product_column_width;
		}

		/**
		* Sets up right sidebar style, and styles needed for it to be shown on front page
		*
		* @since Pukka 1.1
		*
		* @return String containing CSS
		*/
		function sidebarsCSS(){
			$css = '';
			global $pukka_js;
			
			$settings = (object)pukka_theme_style_settings();

			$left_sidebar_enabled = apply_filters('pukka_left_sidebar', $settings->left_sidebar);
			$right_sidebar_enabled = apply_filters('pukka_right_sidebar', $settings->right_sidebar);

			if($left_sidebar_enabled){
				$css .= "\n .has-sidebar-left #wrapper { padding-left: " . ($settings->sidebar_left_width + $settings->sidebar_margins) . "px; }";
				// $css .= "\n .has-sidebar-left #main { padding-left: " . ($settings->sidebar_left_width + $settings->sidebar_margins) . "px; }";
				$css .= "\n #sidebar-left { width: " . $settings->sidebar_left_width  . "px; }";
				$css .= "\n @media all and (max-width: 700px) {";
				$css .= "\n	.has-sidebar-left #wrapper { padding-left: 0px; }";
				$css .= "\n }";
			}
			
			if($right_sidebar_enabled){
				$css .= "\n .has-sidebar-right #wrapper { padding-right: " . ($settings->sidebar_right_width + $settings->sidebar_margins) . "px; }";
				//$css .= "\n #sidebar-right { width: " . $settings->sidebar_right_width . "px; }";
				$css .= "\n @media all and (max-width: 1024px) {";
				$css .= "\n	.has-sidebar-right #wrapper { padding-right: 0px; }";
				$css .= "\n	#sidebar-right { width: 100%; height: auto; position: static; float: left; }";
				$css .= "\n }";
			}
			
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

			return $css;
		}
		
		/**
		 * Generating CSS from user defined options
		 *
		 * @since Pukka 1.0
		 *
		 * @return string Generated CSS
		 */
		public function customThemeCss() {
			$css = '';
			
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
						\n .txt-dk {
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
						\n }
						\n 	
						\n a.button.checkout {
						\n 	background-color: {$primary_color} !important;
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
						\n }
						\n 
						\n a.button.wc-forward {
						\n 	background-color: {$secondary_color} !important;
						\n }
						\n 
						\n @media all and (max-width: 520px) {
						\n 	.product-block .price,
						\n 	.product-block:hover .price,
						\n 	.masonry .product-block:hover .price,
						\n 	.masonry .product-block:hover .price 	{
						\n 		background-color: {$secondary_color};
						\n 	}
						\n }";
			}
			
			return $css;
		}
		
		/**
		 * Printing ad custom <head> code
		 *
		 * @since Pukka 1.0
		 *
		 */
		public function printAdHeadCode() {

			if( pukka_get_option('ad_head_content') != '' ) {
				echo "\n" . pukka_get_option('ad_head_content') . "\n";
			}
		}

	} // Class end

endif; // end if class_exists