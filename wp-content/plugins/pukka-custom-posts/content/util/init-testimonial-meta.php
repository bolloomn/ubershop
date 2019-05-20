<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter('pukka_add_meta_boxes', 'pukka_testimonial_metaboxes', 1, 1);
function pukka_testimonial_metaboxes($pukka_meta_boxes){
	$pukka_meta_boxes[] = array(
		'id' => 'testimonial_meta_1',
		'title' => 'Testimonial info',
		'post_type' => 'testimonial',
		'context' => 'side',
		'priority' => 'high',
		'fields' => array(
			array(
				'title' => 'Author',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'author',
				'type' => 'text',
				'css_classes' => 'pukka-side-meta',
			),
			array(
				'title' => 'Website',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'website',
				'type' => 'text',
				'css_classes' => 'pukka-side-meta',
			),
		)
		
	);

	return $pukka_meta_boxes;
}