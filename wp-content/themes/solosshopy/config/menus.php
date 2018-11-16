<?php
/**
 * Menus configuration.
 *
 * @package Solosshopy
 */

add_action( 'after_setup_theme', 'solosshopy_register_menus', 5 );
/**
 * Register menus.
 */
function solosshopy_register_menus() {

	register_nav_menus( array(
		'top'          => esc_html__( 'Top', 'solosshopy' ),
		'main'         => esc_html__( 'Main', 'solosshopy' ),
		'main_landing' => esc_html__( 'Landing Main', 'solosshopy' ),
		'footer'       => esc_html__( 'Footer', 'solosshopy' ),
		'social'       => esc_html__( 'Social', 'solosshopy' ),
	) );
}
