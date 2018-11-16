<?php
/**
 * Thumbnails configuration.
 *
 * @package Solosshopy
 */

add_action( 'after_setup_theme', 'solosshopy_register_image_sizes', 5 );
/**
 * Register image sizes.
 */
function solosshopy_register_image_sizes() {
	set_post_thumbnail_size( 360, 303, true );

	// Registers a new image sizes.
	add_image_size( 'solosshopy-thumb-s', 150, 150, true );
	add_image_size( 'solosshopy-thumb-m', 460, 460, true );
	add_image_size( 'solosshopy-thumb-l', 860, 484, true );
	add_image_size( 'solosshopy-thumb-l-2', 766, 300, true );
	add_image_size( 'solosshopy-thumb-xl', 1160, 508, true );

	add_image_size( 'solosshopy-thumb-masonry', 560, 9999 );

	add_image_size( 'solosshopy-slider-thumb', 150, 86, true );

	add_image_size( 'solosshopy-woo-cart-product-thumb', 104, 104, true );
	add_image_size( 'solosshopy-thumb-listing-line-product', 270, 400, true );
	add_image_size( 'solosshopy-thumb-grid-line-product', 370, 550, true );

	add_image_size( 'solosshopy-thumb-78-78', 78, 78, true );
	add_image_size( 'solosshopy-thumb-260-147', 260, 147, true );
	add_image_size( 'solosshopy-thumb-260-195', 260, 195, true );
	add_image_size( 'solosshopy-thumb-260-260', 260, 260, true );
	add_image_size( 'solosshopy-thumb-360-270', 360, 270, true );
	add_image_size( 'solosshopy-thumb-480-271', 480, 271, true );
	add_image_size( 'solosshopy-thumb-480-360', 480, 360, true );
	add_image_size( 'solosshopy-thumb-560-315', 560, 315, true );
	add_image_size( 'solosshopy-thumb-660-495', 660, 495, true );
	add_image_size( 'solosshopy-thumb-760-571', 760, 571, true );
}
