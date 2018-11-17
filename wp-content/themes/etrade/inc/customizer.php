<?php
/**
 * Theme Customizer.
 *
 * @package Solosshopy
 */

/**
 * Retrieve a holder for Customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function solosshopy_get_customizer_options() {
	/**
	 * Filter a holder for Customizer options (for theme/plugin developer customization).
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'solosshopy_get_customizer_options' , array(
		'prefix'     => 'solosshopy',
		'capability' => 'edit_theme_options',
		'type'       => 'theme_mod',
		'options'    => array(

			/** `Site Identity` section */
			'show_tagline' => array(
				'title'    => esc_html__( 'Show tagline after logo', 'solosshopy' ),
				'section'  => 'title_tagline',
				'priority' => 60,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'totop_visibility' => array(
				'title'    => esc_html__( 'Show ToTop button', 'solosshopy' ),
				'section'  => 'title_tagline',
				'priority' => 61,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'page_preloader' => array(
				'title'    => esc_html__( 'Show page preloader', 'solosshopy' ),
				'section'  => 'title_tagline',
				'priority' => 62,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),

			/** `General Site settings` panel */
			'general_settings' => array(
				'title'    => esc_html__( 'General Site settings', 'solosshopy' ),
				'priority' => 40,
				'type'     => 'panel',
			),

			/** `Logo & Favicon` section */
			'logo_favicon' => array(
				'title'    => esc_html__( 'Logo &amp; Favicon', 'solosshopy' ),
				'priority' => 25,
				'panel'    => 'general_settings',
				'type'     => 'section',
			),
			'header_logo_type' => array(
				'title'   => esc_html__( 'Logo Type', 'solosshopy' ),
				'section' => 'logo_favicon',
				'default' => 'image',
				'field'   => 'radio',
				'choices' => array(
					'image' => esc_html__( 'Image', 'solosshopy' ),
					'text'  => esc_html__( 'Text', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'header_logo_url' => array(
				'title'           => esc_html__( 'Logo Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload logo image', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => '%s/assets/images/logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_image',
			),
			'invert_header_logo_url' => array(
				'title'           => esc_html__( 'Invert Logo Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload logo image', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => '%s/assets/images/invert-logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_image',
			),
			'retina_header_logo_url' => array(
				'title'           => esc_html__( 'Retina Logo Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload logo for retina-ready devices', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_image',
			),
			'invert_retina_header_logo_url' => array(
				'title'           => esc_html__( 'Invert Retina Logo Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload logo for retina-ready devices', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => false,
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_image',
			),
			'header_logo_font_family' => array(
				'title'           => esc_html__( 'Font Family', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => 'Montserrat, sans-serif',
				'field'           => 'fonts',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_text',
			),
			'header_logo_font_style' => array(
				'title'           => esc_html__( 'Font Style', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => 'normal',
				'field'           => 'select',
				'choices'         => solosshopy_get_font_styles(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_text',
			),
			'header_logo_font_weight' => array(
				'title'           => esc_html__( 'Font Weight', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => '600',
				'field'           => 'select',
				'choices'         => solosshopy_get_font_weight(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_text',
			),
			'header_logo_font_size' => array(
				'title'           => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => '24',
				'field'           => 'number',
				'input_attrs'     => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_text',
			),
			'header_logo_character_set' => array(
				'title'           => esc_html__( 'Character Set', 'solosshopy' ),
				'section'         => 'logo_favicon',
				'default'         => 'latin',
				'field'           => 'select',
				'choices'         => solosshopy_get_character_sets(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_text',
			),

			/** `Breadcrumbs` section */
			'breadcrumbs' => array(
				'title'    => esc_html__( 'Breadcrumbs', 'solosshopy' ),
				'priority' => 30,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'breadcrumbs_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_front_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs on front page', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_page_title' => array(
				'title'   => esc_html__( 'Enable page title in breadcrumbs area', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_path_type' => array(
				'title'   => esc_html__( 'Show full/minified path', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => 'minified',
				'field'   => 'select',
				'choices' => array(
					'full'     => esc_html__( 'Full', 'solosshopy' ),
					'minified' => esc_html__( 'Minified', 'solosshopy' ),
				),
				'type'    => 'control',
			),
			'breadcrumbs_bg_color' => array(
				'title'   => esc_html__( 'Breadcrumbs background color', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => '#f6f6f9',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'breadcrumbs_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => 'repeat',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_repeat(),
				'type'    => 'control',
			),
			'breadcrumbs_bg_position' => array(
				'title'   => esc_html__( 'Background Position', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => 'center',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_position(),
				'type'    => 'control',
			),
			'breadcrumbs_bg_size' => array(
				'title'   => esc_html__( 'Background Size', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => 'auto',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_size(),
				'type'    => 'control',
			),
			'breadcrumbs_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'solosshopy' ),
				'section' => 'breadcrumbs',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_attachment(),
				'type'    => 'control',
			),
			'breadcrumbs_bg_image_opacity' => array(
				'title'       => esc_html__( 'Background image opacity', 'solosshopy' ),
				'section'     => 'breadcrumbs',
				'default'     => 1,
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),

			/** `Social links` section */
			'social_links' => array(
				'title'    => esc_html__( 'Social links', 'solosshopy' ),
				'priority' => 50,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'header_social_links' => array(
				'title'   => esc_html__( 'Show social links in header', 'solosshopy' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_social_links' => array(
				'title'   => esc_html__( 'Show social links in footer', 'solosshopy' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Page Layout` section */
			'page_layout' => array(
				'title'    => esc_html__( 'Page Layout', 'solosshopy' ),
				'priority' => 55,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'header_container_type' => array(
				'title'   => esc_html__( 'Header type', 'solosshopy' ),
				'section' => 'page_layout',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'solosshopy' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'content_container_type' => array(
				'title'   => esc_html__( 'Content type', 'solosshopy' ),
				'section' => 'page_layout',
				'default' => 'boxed',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'solosshopy' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'footer_container_type' => array(
				'title'   => esc_html__( 'Footer type', 'solosshopy' ),
				'section' => 'page_layout',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'solosshopy' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'container_width' => array(
				'title'       => esc_html__( 'Container width (px)', 'solosshopy' ),
				'section'     => 'page_layout',
				'default'     => 1200,
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 960,
					'max'  => 1920,
					'step' => 1,
				),
				'type' => 'control',
			),
			'sidebar_width' => array(
				'title'   => esc_html__( 'Sidebar width', 'solosshopy' ),
				'section' => 'page_layout',
				'default' => '1/4',
				'field'   => 'select',
				'choices' => array(
					'1/3' => '1/3',
					'1/4' => '1/4',
				),
				'sanitize_callback' => 'sanitize_text_field',
				'type'              => 'control',
			),

			/** `Color Scheme` panel */
			'color_scheme' => array(
				'title'       => esc_html__( 'Color Scheme', 'solosshopy' ),
				'description' => esc_html__( 'Configure Color Scheme', 'solosshopy' ),
				'priority'    => 40,
				'type'        => 'panel',
			),

			/** `Regular scheme` section */
			'regular_scheme' => array(
				'title'       => esc_html__( 'Regular scheme', 'solosshopy' ),
				'priority'    => 10,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),
			'regular_accent_color_1' => array(
				'title'   => esc_html__( 'Accent color (1)', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#fa0b42',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_2' => array(
				'title'   => esc_html__( 'Accent color (2)', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_3' => array(
				'title'   => esc_html__( 'Accent color (3)', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#6d6f74',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_4' => array(
				'title'   => esc_html__( 'Accent color (4)', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#2a2d34',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_text_color' => array(
				'title'   => esc_html__( 'Text color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#95989f',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_link_color' => array(
				'title'   => esc_html__( 'Link color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#fa0b42',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_link_hover_color' => array(
				'title'   => esc_html__( 'Link hover color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h1_color' => array(
				'title'   => esc_html__( 'H1 color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h2_color' => array(
				'title'   => esc_html__( 'H2 color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h3_color' => array(
				'title'   => esc_html__( 'H3 color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h4_color' => array(
				'title'   => esc_html__( 'H4 color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h5_color' => array(
				'title'   => esc_html__( 'H5 color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h6_color' => array(
				'title'   => esc_html__( 'H6 color', 'solosshopy' ),
				'section' => 'regular_scheme',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Invert scheme` section */
			'invert_scheme' => array(
				'title'       => esc_html__( 'Invert scheme', 'solosshopy' ),
				'priority'    => 20,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),
			'invert_accent_color_1' => array(
				'title'   => esc_html__( 'Accent color (1)', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
            'invert_accent_color_2' => array(
				'title'   => esc_html__( 'Accent color (2)', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#95989f',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
            'invert_accent_color_3' => array(
				'title'   => esc_html__( 'Accent color (3)', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#37373c',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
            'invert_accent_color_4' => array(
				'title'   => esc_html__( 'Accent color (4)', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#fb3c68',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
            'invert_accent_color_5' => array(
                'title'   => esc_html__( 'Accent color (5)', 'solosshopy' ),
                'section' => 'invert_scheme',
                'default' => '#a8a8a9',
                'field'   => 'hex_color',
                'type'    => 'control',
            ),
            'invert_accent_color_6' => array(
                'title'   => esc_html__( 'Accent color (6)', 'solosshopy' ),
                'section' => 'invert_scheme',
                'default' => '#6d6f74',
                'field'   => 'hex_color',
                'type'    => 'control',
            ),
			'invert_accent_color_7' => array(
                'title'   => esc_html__( 'Accent color (7)', 'solosshopy' ),
                'section' => 'invert_scheme',
                'default' => '#373a41',
                'field'   => 'hex_color',
                'type'    => 'control',
            ),
			'invert_text_color' => array(
				'title'   => esc_html__( 'Text color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#6c6e73',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_link_color' => array(
				'title'   => esc_html__( 'Link color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#fa0b42',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_link_hover_color' => array(
				'title'   => esc_html__( 'Link hover color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h1_color' => array(
				'title'   => esc_html__( 'H1 color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h2_color' => array(
				'title'   => esc_html__( 'H2 color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h3_color' => array(
				'title'   => esc_html__( 'H3 color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h4_color' => array(
				'title'   => esc_html__( 'H4 color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h5_color' => array(
				'title'   => esc_html__( 'H5 color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h6_color' => array(
				'title'   => esc_html__( 'H6 color', 'solosshopy' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Greyscale colors` section */
			'grey_scheme' => array(
				'title'       => esc_html__( 'Greyscale colors', 'solosshopy' ),
				'priority'    => 30,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),

			'grey_color_1' => array(
				'title'   => esc_html__( 'Grey color (1)', 'solosshopy' ),
				'section' => 'grey_scheme',
				'default' => '#e7e7eb',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			'grey_color_2' => array(
				'title'   => esc_html__( 'Grey color (2)', 'solosshopy' ),
				'section' => 'grey_scheme',
				'default' => '#eff0f0',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			'grey_color_3' => array(
				'title'   => esc_html__( 'Grey color (3)', 'solosshopy' ),
				'section' => 'grey_scheme',
				'default' => '#64656e',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			'grey_color_4' => array(
				'title'   => esc_html__( 'Grey color (4)', 'solosshopy' ),
				'section' => 'grey_scheme',
				'default' => '#f6f6f9',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Typography Settings` panel */
			'typography' => array(
				'title'       => esc_html__( 'Typography', 'solosshopy' ),
				'description' => esc_html__( 'Configure typography settings', 'solosshopy' ),
				'priority'    => 50,
				'type'        => 'panel',
			),

			/** `Body text` section */
			'body_typography' => array(
				'title'       => esc_html__( 'Body text', 'solosshopy' ),
				'priority'    => 5,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'body_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'body_typography',
				'default' => 'Open Sans, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'body_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'body_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'body_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'body_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'body_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'body_typography',
				'default'     => '14',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'body_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'body_typography',
				'default'     => '1.64',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'body_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'body_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'body_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'body_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'body_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'body_typography',
				'default' => 'left',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H1 Heading` section */
			'h1_typography' => array(
				'title'    => esc_html__( 'H1 Heading', 'solosshopy' ),
				'priority' => 10,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h1_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'h1_typography',
				'default' => 'Hind, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h1_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'h1_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'h1_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'h1_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'h1_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'h1_typography',
				'default'     => '70',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h1_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'h1_typography',
				'default'     => '1.09',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h1_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'h1_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h1_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'h1_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'h1_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'h1_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H2 Heading` section */
			'h2_typography' => array(
				'title'    => esc_html__( 'H2 Heading', 'solosshopy' ),
				'priority' => 15,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h2_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'h2_typography',
				'default' => 'Hind, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h2_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'h2_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'h2_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'h2_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'h2_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'h2_typography',
				'default'     => '50',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h2_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'h2_typography',
				'default'     => '1.3',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h2_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'h2_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h2_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'h2_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'h2_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'h2_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H3 Heading` section */
			'h3_typography' => array(
				'title'    => esc_html__( 'H3 Heading', 'solosshopy' ),
				'priority' => 20,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h3_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'h3_typography',
				'default' => 'Hind, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h3_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'h3_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'h3_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'h3_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'h3_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'h3_typography',
				'default'     => '26',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h3_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'h3_typography',
				'default'     => '1',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h3_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'h3_typography',
				'default'     => '0.02',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h3_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'h3_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'h3_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'h3_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H4 Heading` section */
			'h4_typography' => array(
				'title'    => esc_html__( 'H4 Heading', 'solosshopy' ),
				'priority' => 25,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h4_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'h4_typography',
				'default' => 'Hind, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h4_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'h4_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'h4_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'h4_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'h4_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'h4_typography',
				'default'     => '20',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h4_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'h4_typography',
				'default'     => '1.45',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h4_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'h4_typography',
				'default'     => '0.02',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h4_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'h4_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'h4_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'h4_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H5 Heading` section */
			'h5_typography' => array(
				'title'    => esc_html__( 'H5 Heading', 'solosshopy' ),
				'priority' => 30,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h5_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'h5_typography',
				'default' => 'Hind, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h5_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'h5_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'h5_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'h5_typography',
				'default' => '500',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'h5_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'h5_typography',
				'default'     => '16',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h5_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'h5_typography',
				'default'     => '1.44',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h5_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'h5_typography',
				'default'     => '0.02',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h5_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'h5_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'h5_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'h5_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H6 Heading` section */
			'h6_typography' => array(
				'title'    => esc_html__( 'H6 Heading', 'solosshopy' ),
				'priority' => 35,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h6_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'h6_typography',
				'default' => 'Hind, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h6_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'h6_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'h6_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'h6_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'h6_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'h6_typography',
				'default'     => '12',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h6_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'h6_typography',
				'default'     => '1.44',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h6_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'h6_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h6_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'h6_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),
			'h6_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'solosshopy' ),
				'section' => 'h6_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => solosshopy_get_text_aligns(),
				'type'    => 'control',
			),

			/** `Breadcrumbs` section */
			'breadcrumbs_typography' => array(
				'title'    => esc_html__( 'Breadcrumbs', 'solosshopy' ),
				'priority' => 45,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'breadcrumbs_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'Open Sans, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'breadcrumbs_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'breadcrumbs_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'breadcrumbs_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'breadcrumbs_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '12',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'breadcrumbs_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '1.75',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'breadcrumbs_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'breadcrumbs_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),

			/** `Meta` section */
			'meta_typography' => array(
				'title'       => esc_html__( 'Meta', 'solosshopy' ),
				'priority'    => 50,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'meta_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'meta_typography',
				'default' => 'Open Sans, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'meta_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'meta_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'meta_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'meta_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'meta_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'meta_typography',
				'default'     => '12',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'meta_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'meta_typography',
				'default'     => '1.75',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'meta_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'meta_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'meta_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'meta_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),

			/** `Main menu` section */
			'main_menu_typography' => array(
				'title'       => esc_html__( 'Main menu', 'solosshopy' ),
				'priority'    => 50,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'main_menu_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'solosshopy' ),
				'section' => 'main_menu_typography',
				'default' => 'Open Sans, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'main_menu_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'main_menu_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'main_menu_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'main_menu_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'main_menu_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'solosshopy' ),
				'section'     => 'main_menu_typography',
				'default'     => '13',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'main_menu_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'solosshopy' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'solosshopy' ),
				'section'     => 'main_menu_typography',
				'default'     => '1.643',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'main_menu_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'solosshopy' ),
				'section'     => 'main_menu_typography',
				'default'     => '0.04',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'main_menu_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'main_menu_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),

			/** `Typography misc` section */
			'misc_styles' => array(
				'title'    => esc_html__( 'Misc', 'solosshopy' ),
				'priority' => 60,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'word_wrap' => array(
				'title'   => esc_html__( 'Enable Word Wrap', 'solosshopy' ),
				'section' => 'misc_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Accent font family` section */
			'accent_typography' => array(
				'title'       => esc_html__( 'Accent Typography', 'solosshopy' ),
				'priority'    => 60,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'
			t_family_1' => array(
				'title'   => esc_html__( 'Accent Font Family 1', 'solosshopy' ),
				'section' => 'accent_typography',
				'default' => 'Dynalight, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'accent_font_font_style_1' => array(
				'title'   => esc_html__( 'Font Style', 'solosshopy' ),
				'section' => 'accent_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => solosshopy_get_font_styles(),
				'type'    => 'control',
			),
			'accent_font_font_weight_1' => array(
				'title'   => esc_html__( 'Font Weight', 'solosshopy' ),
				'section' => 'accent_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => solosshopy_get_font_weight(),
				'type'    => 'control',
			),
			'accent_font_character_set_1' => array(
				'title'   => esc_html__( 'Character Set', 'solosshopy' ),
				'section' => 'accent_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => solosshopy_get_character_sets(),
				'type'    => 'control',
			),


			/** `Header` panel */
			'header_options' => array(
				'title'    => esc_html__( 'Header', 'solosshopy' ),
				'priority' => 60,
				'type'     => 'panel',
			),

			/** `Header styles` section */
			'header_styles' => array(
				'title'    => esc_html__( 'Styles', 'solosshopy' ),
				'priority' => 5,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'header_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'style-1',
				'field'   => 'select',
				'choices' => solosshopy_get_header_layout_options(),
				'type'    => 'control',
			),
			'header_transparent_layout' => array(
				'title'   => esc_html__( 'Header overlay', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
				'active_callback' => '__return_false',
			),
			'header_invert_color_scheme' => array(
				'title'   => esc_html__( 'Enable invert color scheme', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_nav_panel_type' => array(
				'title'   => esc_html__( 'Navigation section type', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'boxed',
				'field'   => 'select',
				'type'    => 'control',
				'choices' => array(
					'fullwidth' => esc_html__( 'Fullwidth', 'solosshopy' ),
					'boxed'     => esc_html__( 'Boxed', 'solosshopy' ),
				),
				'active_callback' => 'solosshopy_is_header_layout_style_5',
			),
			'header_nav_panel_position' => array(
				'title'   => esc_html__( 'Navigation section position', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'static',
				'field'   => 'select',
				'type'    => 'control',
				'choices' => array(
					'static' => esc_html__( 'Static', 'solosshopy' ),
					'over'   => esc_html__( 'Over Content', 'solosshopy' ),
				),
				'active_callback' => 'solosshopy_is_header_layout_style_5',
			),
			'header_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'solosshopy' ),
				'section' => 'header_styles',
				'field'   => 'hex_color',
				'default' => '#23262d',
				'type'    => 'control',
			),
			'header_bg_image' => array(
				'title'   => esc_html__( 'Background Image', 'solosshopy' ),
				'section' => 'header_styles',
				'field'   => 'image',
				'type'    => 'control',
			),
			'header_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'no-repeat',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_repeat(),
				'type'    => 'control',
			),
			'header_bg_position' => array(
				'title'   => esc_html__( 'Background Position', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'center',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_position(),
				'type'    => 'control',
			),
			'header_bg_size' => array(
				'title'   => esc_html__( 'Background Size', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'cover',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_size(),
				'type'    => 'control',
			),
			'header_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'solosshopy' ),
				'section' => 'header_styles',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => solosshopy_get_bg_attachment(),
				'type'    => 'control',
			),

			/** `Header elements` section */
			'header_elements' => array(
				'title'       => esc_html__( 'Header Elements', 'solosshopy' ),
				'priority'    => 15,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'header_search' => array(
				'title'   => esc_html__( 'Show search', 'solosshopy' ),
				'section' => 'header_elements',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_cart' => array(
				'title'           => esc_html__( 'Show woocommerce cart', 'solosshopy' ),
				'section'         => 'header_elements',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_woocommerce_activated',
			),
            'top_switcher' => array(
                'title'           => esc_html__( 'Show currency switcher', 'solosshopy' ),
                'section'         => 'header_elements',
                'default'         => false,
                'field'           => 'checkbox',
                'type'            => 'control',
                'active_callback' => 'solosshopy_is_woocommerce_activated',
            ),
			'header_btn_visibility' => array(
				'title'   => esc_html__( 'Show header call to action button', 'solosshopy' ),
				'section' => 'header_elements',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_btn_text' => array(
				'title'           => esc_html__( 'Header call to action button', 'solosshopy' ),
				'description'     => esc_html__( 'Button text', 'solosshopy' ),
				'section'         => 'header_elements',
				'default'         => esc_html__( 'Make an Appointment', 'solosshopy' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_btn_visible',
			),
			'header_btn_icon' => array(
				'title'           => esc_html__( 'Header button icon', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'header_elements',
				'field'           => 'iconpicker',
				'default'         => 'education_agenda-bookmark',
				'icon_data'       => solosshopy_get_nc_mini_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_btn_visible',
			),
			'header_btn_icon_location' => array(
				'title'   => esc_html__( 'Header icon location', 'solosshopy' ),
				'section' => 'header_elements',
				'default' => 'left',
				'field'   => 'radio',
				'choices' => array(
					'left'   => esc_html__( 'Left', 'solosshopy' ),
					'right'  => esc_html__( 'Right', 'solosshopy' ),
				),
				'type'    => 'control',
				'active_callback' => 'solosshopy_is_header_btn_visible',
			),
			'header_btn_url' => array(
				'title'           => '',
				'description'     => esc_html__( 'Button url', 'solosshopy' ),
				'section'         => 'header_elements',
				'default'         => '%%home_url%%booked',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_btn_visible',
			),
			'header_btn_target' => array(
				'title'           => esc_html__( 'Open Link in New Tab', 'solosshopy' ),
				'section'         => 'header_elements',
				'default'         => false,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_btn_visible',
			),
			'header_btn_style' => array(
				'title'   => esc_html__( 'Header button style', 'solosshopy' ),
				'section' => 'header_elements',
				'default' => 'accent-1',
				'field'   => 'radio',
				'choices' => array(
					'accent-1'  => esc_html__( 'Accent 1', 'solosshopy' ),
					'accent-2'  => esc_html__( 'Accent 2', 'solosshopy' ),
				),
				'type'    => 'control',
				'active_callback' => 'solosshopy_is_header_btn_visible',
			),

			/** `Header contact block` section */
			'header_contact_block' => array(
				'title'       => esc_html__( 'Header Contact Block', 'solosshopy' ),
				'priority'    => 10,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'header_contact_block_visibility' => array(
				'title'   => esc_html__( 'Show Header Contact Block', 'solosshopy' ),
				'section' => 'header_contact_block',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_contact_icon_1' => array(
				'title'           => esc_html__( 'Contact item 1', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-clock3',
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_label_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'default'         => false,
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_text_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'default'         => solosshopy_get_default_contact_information( 'work-time' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_icon_2' => array(
				'title'           => esc_html__( 'Contact item 2', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-map-marker',
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_label_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'default'         => false,
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_text_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'default'         => solosshopy_get_default_contact_information( 'address' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_icon_3' => array(
				'title'           => esc_html__( 'Contact item 3', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-telephone',
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_label_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'default'         => esc_html__( 'Call Us:', 'solosshopy' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),
			'header_contact_text_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'solosshopy' ),
				'section'         => 'header_contact_block',
				'default'         => solosshopy_get_default_contact_information( 'phones' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_contact_block_enable',
			),

			/** `Top Panel` section */
			'header_top_panel' => array(
				'title'    => esc_html__( 'Top Panel', 'solosshopy' ),
				'priority' => 20,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'top_panel_visibility' => array(
				'title'   => esc_html__( 'Enable top panel', 'solosshopy' ),
				'section' => 'header_top_panel',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'top_panel_text' => array(
				'title'           => esc_html__( 'Disclaimer Text', 'solosshopy' ),
				'description'     => esc_html__( 'HTML formatting support', 'solosshopy' ),
				'section'         => 'header_top_panel',
				'default'         => false,
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_enable',
			),
			'top_panel_bg'        => array(
				'title'           => esc_html__( 'Background color', 'solosshopy' ),
				'section'         => 'header_top_panel',
				'default'         => '#f6f5fa',
				'field'           => 'hex_color',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_enable',
			),
			'top_menu_visibility' => array(
				'title'           => esc_html__( 'Show top menu', 'solosshopy' ),
				'section'         => 'header_top_panel',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_enable',
			),
			'login_link_visibility' => array(
				'title'           => esc_html__( 'Show login link', 'solosshopy' ),
				'section'         => 'header_top_panel',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_enable',
			),

			/** `Header contact block` section */
			'header_top_panel_contact_block' => array(
				'title'       => esc_html__( 'Top Panel Contact Block', 'solosshopy' ),
				'description' => esc_html__( 'This block shows only if Top Panel section is enabled!', 'solosshopy' ),
				'priority'    => 25,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'header_top_panel_contact_block_visibility' => array(
				'title'   => esc_html__( 'Show Header Contact Block', 'solosshopy' ),
				'section' => 'header_top_panel_contact_block',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_top_panel_contact_icon_1' => array(
				'title'           => esc_html__( 'Contact item 1', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'header_top_panel_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_contact_block_enable',
			),
			'header_top_panel_contact_label_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'header_top_panel_contact_block',
				'default'         => false,
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_contact_block_enable',
			),
			'header_top_panel_contact_text_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'solosshopy' ),
				'section'         => 'header_top_panel_contact_block',
				'default'         => false,
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_top_panel_contact_block_enable',
			),

			/** `Main Menu` section */
			'header_main_menu' => array(
				'title'    => esc_html__( 'Main Menu', 'solosshopy' ),
				'priority' => 30,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'header_menu_sticky' => array(
				'title'   => esc_html__( 'Enable sticky menu', 'solosshopy' ),
				'section' => 'header_main_menu',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_menu_attributes' => array(
				'title'   => esc_html__( 'Enable description', 'solosshopy' ),
				'section' => 'header_main_menu',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_menu_style' => array(
				'title'   => esc_html__( 'Menu style', 'solosshopy' ),
				'section' => 'header_main_menu',
				'default' => 'style-1',
				'field'   => 'radio',
				'choices' => array(
					'style-1' => esc_html__( 'Style 1', 'solosshopy' ),
					'style-2' => esc_html__( 'Style 2', 'solosshopy' ),
				),
				'type'    => 'control',
			),
			'more_button_type' => array(
				'title'   => esc_html__( 'More Menu Button Type', 'solosshopy' ),
				'section' => 'header_main_menu',
				'default' => 'text',
				'field'   => 'radio',
				'choices' => array(
					'image' => esc_html__( 'Image', 'solosshopy' ),
					'icon'  => esc_html__( 'Icon', 'solosshopy' ),
					'text'  => esc_html__( 'Text', 'solosshopy' ),
				),
				'type'    => 'control',
			),
			'more_button_text' => array(
				'title'           => esc_html__( 'More Menu Button Text', 'solosshopy' ),
				'section'         => 'header_main_menu',
				'default'         => esc_html__( 'More', 'solosshopy' ),
				'field'           => 'input',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_more_button_type_text',
			),
			'more_button_icon' => array(
				'title'           => esc_html__( 'More Menu Button Icon', 'solosshopy' ),
				'section'         => 'header_main_menu',
				'field'           => 'iconpicker',
				'type'            => 'control',
				'default'         => 'arrows-1_minimal-down',
				'active_callback' => 'solosshopy_is_more_button_type_icon',
				'icon_data'       => solosshopy_get_nc_mini_icons_data(),
			),
			'more_button_image_url' => array(
				'title'           => esc_html__( 'More Button Image Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload More Button image', 'solosshopy' ),
				'section'         => 'header_main_menu',
				'default'         => false,
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_more_button_type_image',
			),
			'retina_more_button_image_url' => array(
				'title'           => esc_html__( 'Retina More Button Image Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload More Button image for retina-ready devices', 'solosshopy' ),
				'section'         => 'header_main_menu',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_more_button_type_image',
			),

			/** `Sidebar` section */
			'sidebar_settings' => array(
				'title'    => esc_html__( 'Sidebar', 'solosshopy' ),
				'priority' => 105,
				'type'     => 'section',
			),
			'sidebar_position' => array(
				'title'   => esc_html__( 'Sidebar Position', 'solosshopy' ),
				'section' => 'sidebar_settings',
				'default' => 'one-left-sidebar',
				'field'   => 'select',
				'choices' => array(
					'one-left-sidebar'  => esc_html__( 'Sidebar on left side', 'solosshopy' ),
					'one-right-sidebar' => esc_html__( 'Sidebar on right side', 'solosshopy' ),
					'fullwidth'         => esc_html__( 'No sidebars', 'solosshopy' ),
				),
				'type' => 'control',
			),

			/** `Ads Management` panel */
			'ads_management' => array(
				'title'    => esc_html__( 'Ads Management', 'solosshopy' ),
				'priority' => 110,
				'type'     => 'section',
			),
			'ads_header' => array(
				'title'             => esc_html__( 'Header', 'solosshopy' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => false,
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_home_before_loop' => array(
				'title'             => esc_html__( 'Front Page Before Loop', 'solosshopy' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => false,
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_post_before_content' => array(
				'title'             => esc_html__( 'Post Before Content', 'solosshopy' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => false,
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_post_before_comments' => array(
				'title'             => esc_html__( 'Post Before Comments', 'solosshopy' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => false,
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),

			/** `Footer` panel */
			'footer_options' => array(
				'title'    => esc_html__( 'Footer', 'solosshopy' ),
				'priority' => 110,
				'type'     => 'panel',
			),

			/** `Footer styles` section */
			'footer_styles' => array(
				'title'    => esc_html__( 'Footer Styles', 'solosshopy' ),
				'priority' => 5,
				'panel'    => 'footer_options',
				'type'     => 'section',
			),
			'footer_logo_visibility' => array(
				'title'   => esc_html__( 'Show Footer Logo', 'solosshopy' ),
				'section' => 'footer_styles',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_logo_url' => array(
				'title'           => esc_html__( 'Logo upload', 'solosshopy' ),
				'section'         => 'footer_styles',
				'field'           => 'image',
				'default'         => '%s/assets/images/footer-logo.png',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_logo_enable',
			),
			'invert_footer_logo_url' => array(
				'title'           => esc_html__( 'Invert Logo Upload', 'solosshopy' ),
				'description'     => esc_html__( 'Upload logo image', 'solosshopy' ),
				'section'         => 'footer_styles',
				'default'         => '%s/assets/images/invert-logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_header_logo_image',
			),
			'footer_copyright' => array(
				'title'   => esc_html__( 'Copyright text', 'solosshopy' ),
				'section' => 'footer_styles',
				'default' => solosshopy_get_default_footer_copyright(),
				'field'   => 'textarea',
				'type'    => 'control',
			),
			'footer_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'solosshopy' ),
				'section' => 'footer_styles',
				'default' => 'style-1',
				'field'   => 'select',
				'choices' => solosshopy_get_footer_layout_options(),
				'type' => 'control',
			),
			'footer_bg_first' => array(
				'title'           => esc_html__( 'Footer Background first row color', 'solosshopy' ),
				'section'         => 'footer_styles',
				'default'         => '#1c1e24',
				'field'           => 'hex_color',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_style_1_enable',
			),
			'footer_bg' => array(
				'title'   => esc_html__( 'Footer Background color', 'solosshopy' ),
				'section' => 'footer_styles',
				'default' => '#1c1e24',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'footer_widget_area_visibility' => array(
				'title'   => esc_html__( 'Show Footer Widgets Area', 'solosshopy' ),
				'section' => 'footer_styles',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_widget_columns' => array(
				'title'           => esc_html__( 'Widget Area Columns', 'solosshopy' ),
				'section'         => 'footer_styles',
				'default'         => '4',
				'field'           => 'select',
				'choices'         => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_area_enable',
			),
			'footer_widgets_bg' => array(
				'title'           => esc_html__( 'Footer Widgets Area Background color', 'solosshopy' ),
				'section'         => 'footer_styles',
				'default'         => '#23262d',
				'field'           => 'hex_color',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_area_enable',
			),
			'footer_menu_visibility' => array(
				'title'   => esc_html__( 'Show Footer Menu', 'solosshopy' ),
				'section' => 'footer_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Footer contact block` section */
			'footer_contact_block' => array(
				'title'    => esc_html__( 'Footer Contact Block', 'solosshopy' ),
				'priority' => 10,
				'panel'    => 'footer_options',
				'type'     => 'section',
			),
			'footer_contact_block_visibility' => array(
				'title'   => esc_html__( 'Show Footer Contact Block', 'solosshopy' ),
				'section' => 'footer_contact_block',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_contact_icon_1' => array(
				'title'           => esc_html__( 'Contact item 1', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_label_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'default'         => false,
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_text_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Value (HTML formatting support)', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'default'         => false,
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_icon_2' => array(
				'title'           => esc_html__( 'Contact item 2', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_label_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'default'         => false,
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_text_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Value (HTML formatting support)', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'default'         => false,
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_icon_3' => array(
				'title'           => esc_html__( 'Contact item 3', 'solosshopy' ),
				'description'     => esc_html__( 'Choose icon', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => solosshopy_get_linear_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_label_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'default'         => false,
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),
			'footer_contact_text_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Value (HTML formatting support)', 'solosshopy' ),
				'section'         => 'footer_contact_block',
				'default'         => false,
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_footer_contact_block_enable',
			),

			/** `Blog Settings` panel */
			'blog_settings' => array(
				'title'    => esc_html__( 'Blog Settings', 'solosshopy' ),
				'priority' => 115,
				'type'     => 'panel',
			),

			/** `Blog` section */
			'blog' => array(
				'title'           => esc_html__( 'Blog', 'solosshopy' ),
				'panel'           => 'blog_settings',
				'priority'        => 10,
				'type'            => 'section',
				'active_callback' => 'is_home',
			),
			'blog_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'solosshopy' ),
				'section' => 'blog',
				'default' => 'grid',
				'field'   => 'select',
				'choices' => array(
					'default'          => esc_html__( 'Listing', 'solosshopy' ),
					'default-modern'   => esc_html__( 'Modern Listing', 'solosshopy' ),
					'grid'             => esc_html__( 'Grid', 'solosshopy' ),
					'masonry'          => esc_html__( 'Masonry', 'solosshopy' ),
					'vertical-justify' => esc_html__( 'Vertical Justify', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'blog_layout_columns' => array(
				'title'           => esc_html__( 'Columns', 'solosshopy' ),
				'section'         => 'blog',
				'default'         => '2-cols',
				'field'           => 'select',
				'choices'         => array(
					'2-cols' => esc_html__( '2 columns', 'solosshopy' ),
					'3-cols' => esc_html__( '3 columns', 'solosshopy' ),
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_blog_layout_type_grid_masonry',
			),
			'blog_sticky_type' => array(
				'title'   => esc_html__( 'Sticky label type', 'solosshopy' ),
				'section' => 'blog',
				'default' => 'icon',
				'field'   => 'select',
				'choices' => array(
					'label' => esc_html__( 'Text Label', 'solosshopy' ),
					'icon'  => esc_html__( 'Font Icon', 'solosshopy' ),
					'both'  => esc_html__( 'Text with Icon', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'blog_sticky_icon' => array(
				'title'           => esc_html__( 'Icon for sticky post', 'solosshopy' ),
				'section'         => 'blog',
				'field'           => 'iconpicker',
				'default'         => 'ui-2_favourite-31',
				'icon_data'       => solosshopy_get_nc_mini_icons_data(),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_sticky_icon',
			),
			'blog_sticky_label' => array(
				'title'           => esc_html__( 'Featured Post Label', 'solosshopy' ),
				'description'     => esc_html__( 'Label for sticky post', 'solosshopy' ),
				'section'         => 'blog',
				'default'         => esc_html__( 'Featured', 'solosshopy' ),
				'field'           => 'text',
				'active_callback' => 'solosshopy_is_sticky_text',
				'type'            => 'control',
			),
			'blog_featured_image' => array(
				'title'           => esc_html__( 'Featured image', 'solosshopy' ),
				'section'         => 'blog',
				'default'         => 'fullwidth',
				'field'           => 'select',
				'choices'         => array(
					'small'     => esc_html__( 'Small', 'solosshopy' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'solosshopy' ),
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_blog_featured_image',
			),
			'blog_posts_content' => array(
				'title'   => esc_html__( 'Post content', 'solosshopy' ),
				'section' => 'blog',
				'default' => 'excerpt',
				'field'   => 'select',
				'choices' => array(
					'excerpt' => esc_html__( 'Only excerpt', 'solosshopy' ),
					'full'    => esc_html__( 'Full content', 'solosshopy' ),
					'none'    => esc_html__( 'Hide', 'solosshopy' ),
				),
				'type' => 'control',
			),
			'blog_posts_content_length' => array(
				'title'           => esc_html__( 'Number of words in the excerpt', 'solosshopy' ),
				'section'         => 'blog',
				'default'         => '60',
				'field'           => 'number',
				'input_attrs'     => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_blog_posts_content_type_excerpt',
			),
			'blog_read_more_btn' => array(
				'title'   => esc_html__( 'Show Read More button', 'solosshopy' ),
				'section' => 'blog',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_read_more_text' => array(
				'title'           => esc_html__( 'Read More button text', 'solosshopy' ),
				'section'         => 'blog',
				'default'         => esc_html__( 'Read more', 'solosshopy' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_blog_read_more_btn_enable',
			),
			'blog_post_author' => array(
				'title'   => esc_html__( 'Show post author', 'solosshopy' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_publish_date' => array(
				'title'   => esc_html__( 'Show publish date', 'solosshopy' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_categories' => array(
				'title'   => esc_html__( 'Show categories', 'solosshopy' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_tags' => array(
				'title'   => esc_html__( 'Show tags', 'solosshopy' ),
				'section' => 'blog',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_comments' => array(
				'title'   => esc_html__( 'Show comments', 'solosshopy' ),
				'section' => 'blog',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Post` section */
			'blog_post' => array(
				'title'           => esc_html__( 'Post', 'solosshopy' ),
				'panel'           => 'blog_settings',
				'priority'        => 20,
				'type'            => 'section',
				'active_callback' => 'callback_single',
			),
			'single_post_author' => array(
				'title'   => esc_html__( 'Show post author', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_publish_date' => array(
				'title'   => esc_html__( 'Show publish date', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_categories' => array(
				'title'   => esc_html__( 'Show categories', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_tags' => array(
				'title'   => esc_html__( 'Show tags', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_comments' => array(
				'title'   => esc_html__( 'Show comments', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_author_block' => array(
				'title'   => esc_html__( 'Enable the author block after each post', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_navigation' => array(
				'title'   => esc_html__( 'Enable post navigation', 'solosshopy' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Related Posts` section */
			'related_posts' => array(
				'title'           => esc_html__( 'Related posts block', 'solosshopy' ),
				'panel'           => 'blog_settings',
				'priority'        => 30,
				'type'            => 'section',
				'active_callback' => 'callback_single',
			),
			'related_posts_visible' => array(
				'title'   => esc_html__( 'Show related posts block', 'solosshopy' ),
				'section' => 'related_posts',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'related_posts_block_title' => array(
				'title'           => esc_html__( 'Related posts block title', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => esc_html__( 'Latest posts', 'solosshopy' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_count' => array(
				'title'           => esc_html__( 'Number of post', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => '2',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_grid' => array(
				'title'           => esc_html__( 'Layout', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => '2',
				'field'           => 'select',
				'choices'         => array(
					'2' => esc_html__( '2 columns', 'solosshopy' ),
					'3' => esc_html__( '3 columns', 'solosshopy' ),
					'4' => esc_html__( '4 columns', 'solosshopy' ),
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_title' => array(
				'title'           => esc_html__( 'Show post title', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_title_length' => array(
				'title'           => esc_html__( 'Number of words in the title', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => '10',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_image' => array(
				'title'           => esc_html__( 'Show post image', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_content' => array(
				'title'           => esc_html__( 'Display content', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => 'hide',
				'field'           => 'select',
				'choices'         => array(
					'hide'         => esc_html__( 'Hide', 'solosshopy' ),
					'post_excerpt' => esc_html__( 'Excerpt', 'solosshopy' ),
					'post_content' => esc_html__( 'Content', 'solosshopy' ),
				),
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_content_length' => array(
				'title'           => esc_html__( 'Number of words in the content', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => '10',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_categories' => array(
				'title'           => esc_html__( 'Show post categories', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_tags' => array(
				'title'           => esc_html__( 'Show post tags', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => false,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_author' => array(
				'title'           => esc_html__( 'Show post author', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_publish_date' => array(
				'title'           => esc_html__( 'Show post publish date', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),
			'related_posts_comment_count' => array(
				'title'           => esc_html__( 'Show post comment count', 'solosshopy' ),
				'section'         => 'related_posts',
				'default'         => false,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'solosshopy_is_related_posts_enable',
			),

			/** `Badge` section */
			'onsale_badge_bg' => array(
				'title'   => esc_html__( 'Onsale badge background', 'solosshopy' ),
				'section' => 'woocommerce_product_catalog',
				'default' => '#fa0b42',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'featured_badge_bg' => array(
				'title'   => esc_html__( 'Featured badge background', 'solosshopy' ),
				'section' => 'woocommerce_product_catalog',
				'default' => '#febf01',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'new_badge_bg' => array(
				'title'   => esc_html__( 'New badge background', 'solosshopy' ),
				'section' => 'woocommerce_product_catalog',
				'default' => '#23262d',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `404` panel */
			'page_404_options' => array(
				'title'    => esc_html__( '404 Page Style', 'solosshopy' ),
				'priority' => 130,
				'type'     => 'section',
			),
			'page_404_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'solosshopy' ),
				'section' => 'page_404_options',
				'field'   => 'hex_color',
				'default' => '#ffffff',
				'type'    => 'control',
			),
			'page_404_image' => array(
				'title'   => esc_html__( '404 Image', 'solosshopy' ),
				'section' => 'page_404_options',
				'field'   => 'image',
				'default' => '%s/assets/images/404.jpg',
				'type'    => 'control',
			),
			'page_404_text_color' => array(
				'title'       => esc_html__( 'Text Color', 'solosshopy' ),
				'description' => esc_html__( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'solosshopy' ),
				'section'     => 'page_404_options',
				'default'     => 'dark',
				'field'       => 'select',
				'choices'     => solosshopy_get_text_color(),
				'type'        => 'control',
			),
			'page_404_btn_style_preset' => array(
				'title'   => esc_html__( 'Button Style Preset', 'solosshopy' ),
				'section' => 'page_404_options',
				'default' => 'accent-1',
				'field'   => 'select',
				'choices' => solosshopy_get_btn_style_presets(),
				'type'    => 'control',
			),
		),
	) );
}

/**
 * Return true if setting is value. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @param  string $setting Setting name to check.
 * @param  string $value   Setting value to compare.
 * @return bool
 */
function solosshopy_is_setting( $control, $setting, $value ) {

	if ( $value == $control->manager->get_setting( $setting )->value() ) {
		return true;
	}

	return false;
}

/**
 * Return true if value of passed setting is not equal with passed value.
 *
 * @param  object $control Parent control.
 * @param  string $setting Setting name to check.
 * @param  string $value   Setting value to compare.
 * @return bool
 */
function solosshopy_is_not_setting( $control, $setting, $value ) {

	if ( $value !== $control->manager->get_setting( $setting )->value() ) {
		return true;
	}

	return false;
}

/**
 * Return true if logo in header has image type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_logo_image( $control ) {
	return solosshopy_is_setting( $control, 'header_logo_type', 'image' );
}

/**
 * Return true if logo in header has text type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_logo_text( $control ) {
	return solosshopy_is_setting( $control, 'header_logo_type', 'text' );
}

/**
 * Return blog-featured-image true if blog layout type is default. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_blog_featured_image( $control ) {
	return solosshopy_is_setting( $control, 'blog_layout_type', 'default' );
}

/**
 * Return true if sticky label type set to text or text with icon.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_sticky_text( $control ) {
	return solosshopy_is_not_setting( $control, 'blog_sticky_type', 'icon' );
}

/**
 * Return true if sticky label type set to icon or text with icon.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_sticky_icon( $control ) {
	return solosshopy_is_not_setting( $control, 'blog_sticky_type', 'label' );
}

/**
 * Return true if More button (in the main menu) has image type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_more_button_type_image( $control ) {
	return solosshopy_is_setting( $control, 'more_button_type', 'image' );
}

/**
 * Return true if More button (in the main menu) has text type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_more_button_type_text( $control ) {
	return solosshopy_is_setting( $control, 'more_button_type', 'text' );
}

/**
 * Return true if More button (in the main menu) has icon type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_more_button_type_icon( $control ) {
	return solosshopy_is_setting( $control, 'more_button_type', 'icon' );
}

/**
 * Return true if option Show header call to action button is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_btn_enable( $control ) {
	return solosshopy_is_setting( $control, 'header_btn_visibility', true );
}

/**
 * Return true if option Add button icon is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_btn_icon_enable( $control ) {
	return solosshopy_is_setting( $control, 'header_btn_visibility', true ) && solosshopy_is_setting( $control, 'header_btn_add_btn_icon', true );
}

/**
 * Return true if option Show Header Contact Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_contact_block_enable( $control ) {
	return solosshopy_is_setting( $control, 'header_contact_block_visibility', true );
}

/**
 * Return true if option Show Top panel Contact Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_top_panel_contact_block_enable( $control ) {
	return solosshopy_is_setting( $control, 'header_top_panel_contact_block_visibility', true );
}

/**
 * Return true if option Show Footer Contact Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_footer_contact_block_enable( $control ) {
	return solosshopy_is_setting( $control, 'footer_contact_block_visibility', true );
}

/**
 * Return true if option Show Related Posts Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_related_posts_enable( $control ) {
	return solosshopy_is_setting( $control, 'related_posts_visible', true );
}

/**
 * Return true if option Enable Top Panel is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_top_panel_enable( $control ) {
	return solosshopy_is_setting( $control, 'top_panel_visibility', true );
}

/**
 * Return true if option Show header call to action button is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_btn_visible( $control ) {
	return solosshopy_is_setting( $control, 'header_btn_visibility', true );
}

/**
 * Return true if option Show Footer Logo is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_footer_logo_enable( $control ) {
	return solosshopy_is_setting( $control, 'footer_logo_visibility', true );
}

/**
 * Return true if option Show Footer Widgets Area is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_footer_area_enable( $control ) {
	return solosshopy_is_setting( $control, 'footer_widget_area_visibility', true );
}

/**
 * Return true if option Footer style is layout-1. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_footer_style_1_enable( $control ) {
	return solosshopy_is_setting( $control, 'footer_layout_type', 'style-1' );
}

/**
 * Return true if option Blog posts content is excerpt. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_blog_posts_content_type_excerpt( $control ) {
	return solosshopy_is_setting( $control, 'blog_posts_content', 'excerpt' );
}

/**
 * Return true if option Show Read More button is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_blog_read_more_btn_enable( $control ) {
	return solosshopy_is_setting( $control, 'blog_read_more_btn', true );
}

/**
 * Return true if Blog layout selected Grid or Masonry. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_blog_layout_type_grid_masonry( $control ) {
	if ( in_array( $control->manager->get_setting( 'blog_layout_type' )->value(), array( 'grid', 'masonry' ) ) ) {
		return true;
	}

	return false;
}

/**
 * Return true if option Header Layout type is style-5. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function solosshopy_is_header_layout_style_5( $control ) {
	return solosshopy_is_setting( $control, 'header_layout_type', 'style-5' );
}

/**
 * Get default header layouts.
 *
 * @since  1.0.0
 * @return array
 */
function solosshopy_get_header_layout_options() {
	return apply_filters( 'solosshopy_header_layout_options', array(
		'style-1' => esc_html__( 'Style 1', 'solosshopy' ),
		'style-2' => esc_html__( 'Style 2', 'solosshopy' ),
		'style-3' => esc_html__( 'Style 3', 'solosshopy' ),
		'style-4' => esc_html__( 'Style 4', 'solosshopy' ),
		'style-5' => esc_html__( 'Style 5', 'solosshopy' ),
		'style-6' => esc_html__( 'Style 6', 'solosshopy' ),
		'style-7' => esc_html__( 'Style 7', 'solosshopy' ),
	) );
}

/**
 * Get default footer layouts.
 *
 * @since  1.0.0
 * @return array
 */
function solosshopy_get_footer_layout_options() {
	return apply_filters( 'solosshopy_footer_layout_options', array(
		'style-1' => esc_html__( 'Style 1', 'solosshopy' ),
		'style-2' => esc_html__( 'Style 2', 'solosshopy' ),
		'style-3' => esc_html__( 'Style 3', 'solosshopy' ),
	) );
}

/**
 * Get default header layouts options for Post Meta boxes
 *
 * @return array
 */
function solosshopy_get_header_layout_pm_options() {
	$inherit_option = array(
		'inherit' => array(
			'label' => esc_html__( 'Inherit', 'solosshopy' ),
		),
	);

	$header_layouts = solosshopy_get_header_layout_options();
	$options        = array();

	foreach ( $header_layouts as $layout => $label ) {
		$options[ $layout ] = array(
			'label' => $label,
			'slave' => 'header_layout_type_' . str_replace( '-', '_', $layout ),
		);
	}

	return array_merge( $inherit_option, $options );
}

/**
 * Get default footer layouts options for Post Meta boxes
 *
 * @return array
 */
function solosshopy_get_footer_layout_pm_options() {
	$inherit_option = array(
		'inherit' => esc_html__( 'Inherit', 'solosshopy' ),
	);

	$options = solosshopy_get_footer_layout_options();

	return array_merge( $inherit_option, $options );
}

// Change native customizer control (based on WordPress core).
add_action( 'customize_register', 'solosshopy_customizer_change_core_controls', 20 );

// Bind JS handlers to instantly live-preview changes.
add_action( 'customize_preview_init', 'solosshopy_customize_preview_js' );

/**
 * Change native customize control (based on WordPress core).
 *
 * @since 1.0.0
 * @param  object $wp_customize Object wp_customize.
 * @return void
 */
function solosshopy_customizer_change_core_controls( $wp_customize ) {
	$wp_customize->get_control( 'site_icon' )->section         = 'solosshopy_logo_favicon';
	$wp_customize->get_section( 'background_image' )->priority = 45;
	$wp_customize->get_control( 'background_color' )->label    = esc_html__( 'Body Background Color', 'solosshopy' );

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function solosshopy_customize_preview_js() {
	wp_enqueue_script( 'solosshopy-customize-preview', SOLOSSHOPY_THEME_JS . '/customize-preview.js', array( 'customize-preview' ), '1.0', true );
}

// Typography utility function
/**
 * Get font styles
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_font_styles() {
	return apply_filters( 'solosshopy_get_font_styles', array(
		'normal'  => esc_html__( 'Normal', 'solosshopy' ),
		'italic'  => esc_html__( 'Italic', 'solosshopy' ),
		'oblique' => esc_html__( 'Oblique', 'solosshopy' ),
		'inherit' => esc_html__( 'Inherit', 'solosshopy' ),
	) );
}

/**
 * Get character sets
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_character_sets() {
	return apply_filters( 'solosshopy_get_character_sets', array(
		'latin'        => esc_html__( 'Latin', 'solosshopy' ),
		'greek'        => esc_html__( 'Greek', 'solosshopy' ),
		'greek-ext'    => esc_html__( 'Greek Extended', 'solosshopy' ),
		'vietnamese'   => esc_html__( 'Vietnamese', 'solosshopy' ),
		'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'solosshopy' ),
		'latin-ext'    => esc_html__( 'Latin Extended', 'solosshopy' ),
		'cyrillic'     => esc_html__( 'Cyrillic', 'solosshopy' ),
	) );
}

/**
 * Get text aligns
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_text_aligns() {
	return apply_filters( 'solosshopy_get_text_aligns', array(
		'inherit' => esc_html__( 'Inherit', 'solosshopy' ),
		'center'  => esc_html__( 'Center', 'solosshopy' ),
		'justify' => esc_html__( 'Justify', 'solosshopy' ),
		'left'    => esc_html__( 'Left', 'solosshopy' ),
		'right'   => esc_html__( 'Right', 'solosshopy' ),
	) );
}

/**
 * Get font weights
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_font_weight() {
	return apply_filters( 'solosshopy_get_font_weight', array(
		'100' => '100',
		'200' => '200',
		'300' => '300',
		'400' => '400',
		'500' => '500',
		'600' => '600',
		'700' => '700',
		'800' => '800',
		'900' => '900',
	) );
}

// Background utility function
/**
 * Get background position
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_bg_position() {
	return apply_filters( 'solosshopy_get_bg_position', array(
		'top-left'      => esc_html__( 'Top Left', 'solosshopy' ),
		'top-center'    => esc_html__( 'Top Center', 'solosshopy' ),
		'top-right'     => esc_html__( 'Top Right', 'solosshopy' ),
		'center-left'   => esc_html__( 'Middle Left', 'solosshopy' ),
		'center'        => esc_html__( 'Middle Center', 'solosshopy' ),
		'center-right'  => esc_html__( 'Middle Right', 'solosshopy' ),
		'bottom-left'   => esc_html__( 'Bottom Left', 'solosshopy' ),
		'bottom-center' => esc_html__( 'Bottom Center', 'solosshopy' ),
		'bottom-right'  => esc_html__( 'Bottom Right', 'solosshopy' ),
	) );
}

/**
 * Get background size
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_bg_size() {
	return apply_filters( 'solosshopy_get_bg_size', array(
		'auto'    => esc_html__( 'Auto', 'solosshopy' ),
		'cover'   => esc_html__( 'Cover', 'solosshopy' ),
		'contain' => esc_html__( 'Contain', 'solosshopy' ),
	) );
}

/**
 * Get background repeat
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_bg_repeat() {
	return apply_filters( 'solosshopy_get_bg_repeat', array(
		'no-repeat' => esc_html__( 'No Repeat', 'solosshopy' ),
		'repeat'    => esc_html__( 'Tile', 'solosshopy' ),
		'repeat-x'  => esc_html__( 'Tile Horizontally', 'solosshopy' ),
		'repeat-y'  => esc_html__( 'Tile Vertically', 'solosshopy' ),
	) );
}

/**
 * Get background attachment
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_bg_attachment() {
	return apply_filters( 'solosshopy_get_bg_attachment', array(
		'scroll' => esc_html__( 'Scroll', 'solosshopy' ),
		'fixed'  => esc_html__( 'Fixed', 'solosshopy' ),
	) );
}

/**
 * Get text color
 *
 * @since 1.0.0
 * @return array
 */
function solosshopy_get_text_color() {
	return apply_filters( 'solosshopy_get_text_color', array(
		'light' => esc_html__( 'Light', 'solosshopy' ),
		'dark'  => esc_html__( 'Dark', 'solosshopy' ),
	) );
}

/**
 * Return array of arguments for dynamic CSS module
 *
 * @return array
 */
function solosshopy_get_dynamic_css_options() {
	return apply_filters( 'solosshopy_get_dynamic_css_options', array(
		'prefix'        => 'solosshopy',
		'type'          => 'theme_mod',
		'parent_handle' => 'solosshopy-theme-style',
		'single'        => true,
		'css_files'     => array(
			solosshopy_get_locate_template( 'assets/css/dynamic.css' ),

			solosshopy_get_locate_template( 'assets/css/dynamic/site/elements.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/header.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/forms.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/social.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/menus.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/post.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/navigation.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/footer.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/misc.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/site/buttons.css' ),

			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/widget-default.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/taxonomy-tiles.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/image-grid.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/carousel.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/smart-slider.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/instagram.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/custom-posts.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/playlist-slider.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/featured-posts-block.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/news-smart-box.css' ),
			solosshopy_get_locate_template( 'assets/css/dynamic/widgets/contact-information.css' ),
		),
		'options' => array(
			'header_logo_font_style',
			'header_logo_font_weight',
			'header_logo_font_size',
			'header_logo_font_family',

			'body_font_style',
			'body_font_weight',
			'body_font_size',
			'body_line_height',
			'body_font_family',
			'body_letter_spacing',
			'body_text_align',

			'h1_font_style',
			'h1_font_weight',
			'h1_font_size',
			'h1_line_height',
			'h1_font_family',
			'h1_letter_spacing',
			'h1_text_align',

			'h2_font_style',
			'h2_font_weight',
			'h2_font_size',
			'h2_line_height',
			'h2_font_family',
			'h2_letter_spacing',
			'h2_text_align',

			'h3_font_style',
			'h3_font_weight',
			'h3_font_size',
			'h3_line_height',
			'h3_font_family',
			'h3_letter_spacing',
			'h3_text_align',

			'h4_font_style',
			'h4_font_weight',
			'h4_font_size',
			'h4_line_height',
			'h4_font_family',
			'h4_letter_spacing',
			'h4_text_align',

			'h5_font_style',
			'h5_font_weight',
			'h5_font_size',
			'h5_line_height',
			'h5_font_family',
			'h5_letter_spacing',
			'h5_text_align',

			'h6_font_style',
			'h6_font_weight',
			'h6_font_size',
			'h6_line_height',
			'h6_font_family',
			'h6_letter_spacing',
			'h6_text_align',

			'breadcrumbs_font_style',
			'breadcrumbs_font_weight',
			'breadcrumbs_font_size',
			'breadcrumbs_line_height',
			'breadcrumbs_font_family',
			'breadcrumbs_letter_spacing',
			'breadcrumbs_bg_color',
			'breadcrumbs_bg_repeat',
			'breadcrumbs_bg_position',
			'breadcrumbs_bg_attachment',
			'breadcrumbs_bg_size',
			'breadcrumbs_bg_image_opacity',

			'meta_font_style',
			'meta_font_weight',
			'meta_font_size',
			'meta_line_height',
			'meta_font_family',
			'meta_letter_spacing',

			'accent_font_family_1',
			'accent_font_weight_1',
			'accent_font_style_1',
			'accent_character_set_1',

			'main_menu_font_style',
			'main_menu_font_weight',
			'main_menu_font_size',
			'main_menu_line_height',
			'main_menu_font_family',
			'main_menu_letter_spacing',

			'regular_accent_color_1',
			'regular_accent_color_2',
			'regular_accent_color_3',
			'regular_accent_color_4',
			'regular_text_color',
			'regular_link_color',
			'regular_link_hover_color',
			'regular_h1_color',
			'regular_h2_color',
			'regular_h3_color',
			'regular_h4_color',
			'regular_h5_color',
			'regular_h6_color',

			'invert_accent_color_1',
			'invert_accent_color_2',
            'invert_accent_color_3',
            'invert_accent_color_4',
            'invert_accent_color_5',
            'invert_accent_color_6',
            'invert_accent_color_7',
			'invert_text_color',
			'invert_link_color',
			'invert_link_hover_color',
			'invert_h1_color',
			'invert_h2_color',
			'invert_h3_color',
			'invert_h4_color',
			'invert_h5_color',
			'invert_h6_color',

			'grey_color_1',
			'grey_color_2',
			'grey_color_3',
			'grey_color_4',

			'header_bg_color',
			'header_bg_image',
			'header_bg_repeat',
			'header_bg_position',
			'header_bg_attachment',
			'header_bg_size',

			'page_404_bg_color',

			'top_panel_bg',

			'container_width',

			'footer_widgets_bg',
			'footer_bg_first',
			'footer_bg',

			'onsale_badge_bg',
			'featured_badge_bg',
			'new_badge_bg',
		),
	) );
}

/**
 * Return array of arguments for Google Font loader module.
 *
 * @since  1.0.0
 * @return array
 */
function solosshopy_get_fonts_options() {
	return apply_filters( 'solosshopy_get_fonts_options', array(
		'prefix'  => 'solosshopy',
		'type'    => 'theme_mod',
		'single'  => true,
		'options' => array(
			'body' => array(
				'family'  => 'body_font_family',
				'style'   => 'body_font_style',
				'weight'  => 'body_font_weight',
				'charset' => 'body_character_set',
			),
			'h1' => array(
				'family'  => 'h1_font_family',
				'style'   => 'h1_font_style',
				'weight'  => 'h1_font_weight',
				'charset' => 'h1_character_set',
			),
			'h2' => array(
				'family'  => 'h2_font_family',
				'style'   => 'h2_font_style',
				'weight'  => 'h2_font_weight',
				'charset' => 'h2_character_set',
			),
			'h3' => array(
				'family'  => 'h3_font_family',
				'style'   => 'h3_font_style',
				'weight'  => 'h3_font_weight',
				'charset' => 'h3_character_set',
			),
			'h4' => array(
				'family'  => 'h4_font_family',
				'style'   => 'h4_font_style',
				'weight'  => 'h4_font_weight',
				'charset' => 'h4_character_set',
			),
			'h5' => array(
				'family'  => 'h5_font_family',
				'style'   => 'h5_font_style',
				'weight'  => 'h5_font_weight',
				'charset' => 'h5_character_set',
			),
			'h6' => array(
				'family'  => 'h6_font_family',
				'style'   => 'h6_font_style',
				'weight'  => 'h6_font_weight',
				'charset' => 'h6_character_set',
			),
			'meta' => array(
				'family'  => 'meta_font_family',
				'style'   => 'meta_font_style',
				'weight'  => 'meta_font_weight',
				'charset' => 'meta_character_set',
			),
			'header_logo' => array(
				'family'  => 'header_logo_font_family',
				'style'   => 'header_logo_font_style',
				'weight'  => 'header_logo_font_weight',
				'charset' => 'header_logo_character_set',
			),
			'breadcrumbs' => array(
				'family'  => 'breadcrumbs_font_family',
				'style'   => 'breadcrumbs_font_style',
				'weight'  => 'breadcrumbs_font_weight',
				'charset' => 'breadcrumbs_character_set',
			),
			'accent_font_family' => array(
				'family'  => 'accent_font_family_1',
				'style'   => 'accent_font_font_style_1',
				'weight'  => 'accent_font_font_weight_1',
				'charset' => 'accent_font_character_set_1',
			),
		),
	) );
}

/**
 * Get default footer copyright.
 *
 * @since  1.0.0
 * @return string
 */
function solosshopy_get_default_footer_copyright() {
	return esc_html__( '&copy; %%year%% %%site-name%%. All Rights Reserved.', 'solosshopy' );
}

/**
 * Get default contact information.
 *
 * @since  1.0.0
 * @return string
 */
function solosshopy_get_default_contact_information( $value ) {
	$contact_information = array(
		'work-time' => sprintf( '%1$s<br>%2$s', esc_html__( 'Mon – Fri: 10AM – 7PM;', 'solosshopy' ), esc_html__( 'Sat – Sun: 10AM – 3PM', 'solosshopy' ) ),
		'phones'    => sprintf( '<a href="tel:#">%1$s</a>', esc_html__( '(719) 445-2808', 'solosshopy' ) ),
		'info'      => esc_html__( '24/7 Emergency Service', 'solosshopy' ),
		'address'   => sprintf( '%1$s<br>%2$s', esc_html__( '4578 Marmora Road,', 'solosshopy' ), esc_html__( 'Glasgow', 'solosshopy' ) ),
	);

	return $contact_information[ $value ];
}

/**
 * Get FontAwesome icons set
 *
 * @return array
 */
function solosshopy_get_icons_set() {

	static $font_icons;

	if ( ! $font_icons ) {

		ob_start();

		include SOLOSSHOPY_THEME_DIR . '/assets/js/icons.json';
		$json = ob_get_clean();

		$font_icons = array();
		$icons      = json_decode( $json, true );

		foreach ( $icons['icons'] as $icon ) {
			$font_icons[] = $icon['id'];
		}
	}

	return $font_icons;
}

/**
 * Get nc-outline icons set.
 *
 * @return array
 */
function solosshopy_get_nc_outline_icons_set() {

	static $nc_outline_icons;

	if ( ! $nc_outline_icons ) {
		ob_start();

		include SOLOSSHOPY_THEME_DIR . '/assets/css/nucleo-outline.css';

		$result = ob_get_clean();

		preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $matches );

		if ( ! is_array( $matches ) || empty( $matches[1] ) ) {
			return;
		}

		$nc_outline_icons = $matches[1];
	}

	return $nc_outline_icons;
}

/**
 * Get nc-mini icons set.
 *
 * @return array
 */
function solosshopy_get_nc_mini_icons_set() {

	static $nc_mini_icons;

	if ( ! $nc_mini_icons ) {
		ob_start();

		include SOLOSSHOPY_THEME_DIR . '/assets/css/nucleo-mini.css';

		$result = ob_get_clean();

		preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $matches );

		if ( ! is_array( $matches ) || empty( $matches[1] ) ) {
			return;
		}

		$nc_mini_icons = $matches[1];
	}

	return $nc_mini_icons;
}

/**
 * Get nc-outline icons data for iconpicker control.
 *
 * @return array
 */
function solosshopy_get_nc_outline_icons_data() {
	return apply_filters( 'solosshopy_nc_outline_icons_data', array(
		'icon_set'    => 'solosshopyNucleoOutlineIcons',
		'icon_css'    => SOLOSSHOPY_THEME_URI . '/assets/css/nucleo-outline.css',
		'icon_base'   => 'nc-icon-outline',
		'icon_prefix' => '',
		'icons'       => solosshopy_get_nc_outline_icons_set(),
	) );
}

/**
 * Get nc-mini icons data for iconpicker control.
 *
 * @return array
 */
function solosshopy_get_nc_mini_icons_data() {
	return apply_filters( 'solosshopy_nc_mini_icons_data', array(
		'icon_set'    => 'solosshopyNucleoMiniIcons',
		'icon_css'    => SOLOSSHOPY_THEME_URI . '/assets/css/nucleo-mini.css',
		'icon_base'   => 'nc-icon-mini',
		'icon_prefix' => '',
		'icons'       => solosshopy_get_nc_mini_icons_set(),
	) );
}
/**
 * Get nc-mini icons data for iconpicker control.
 *
 * @return array
 */
function solosshopy_get_linear_icons_data() {
    return apply_filters( 'solosshopy_linear_icons_data', array(
        'icon_set'    => 'solosshopyLinearIcons',
        'icon_css'    => SOLOSSHOPY_THEME_URI . '/assets/css/linearicons.css',
        'icon_base'   => 'linearicon',
        'icon_prefix' => '',
        'icons'       => solosshopy_get_linear_icons_set(),
    ) );
}
/**
 * Get linear icons set
 *
 * @return array
 */
function solosshopy_get_linear_icons_set() {

    static $linear_icons;

    if ( ! $linear_icons ) {
        ob_start();

        include SOLOSSHOPY_THEME_DIR . '/assets/css/linearicons.css';

        $result = ob_get_clean();

        preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $matches );

        if ( ! is_array( $matches ) || empty( $matches[1] ) ) {
            return;
        }

        $linear_icons = $matches[1];
    }

    return $linear_icons;
}

/**
 * Get header button style presets.
 *
 * @return array
 */
function solosshopy_get_btn_style_presets() {
	return apply_filters( 'solosshopy_get_btn_style_presets', array(
		'accent-1' => esc_html__( 'Accent button 1', 'solosshopy' ),
		'accent-2' => esc_html__( 'Accent button 2', 'solosshopy' ),
	) );
}

/**
 * Return sanitized theme mod value.
 *
 * @param  string       $mod               Mod name to get.
 * @param  bool         $use_default       User or not default value.
 * @param  string|array $sanitize_callback Sanitize callback name.
 * @return mixed
 */
function solosshopy_get_mod( $mod = null, $use_default = false, $sanitize_callback = null ) {

    if ( ! $mod ) {
        return false;
    }

    $default = false;

    if ( true === $use_default ) {
        $default = solosshopy_theme()->customizer->get_default( $mod );
    }

    $value = get_theme_mod( $mod, $default );

    if ( is_callable( $sanitize_callback ) ) {
        return call_user_func( $sanitize_callback, $value );
    } else {
        return $value;
    }

}