<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter('pukka_add_meta_boxes', 'pukka_portofolio_metaboxes', 1, 1);
function pukka_portofolio_metaboxes($pukka_meta_boxes){
	$pukka_meta_boxes[] = array(
		'id' => 'portofolio_meta_1',
		'title' => 'Portfolio info',
		'post_type' => 'portfolio',
		'context' => 'side',
		'priority' => 'high',
		'fields' => array(
			array(
				'title' => 'Link',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'link',
				'type' => 'text',
				'css_classes' => 'pukka-side-meta',
			),
			array(
				'title' => 'Secondary image',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'secondary_image_id',
				'type' => 'file',
				'css_classes' => 'pukka-side-meta',
			),
			array(
				'title' => 'Secondary image URL',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'secondary_image_url',
				'type' => 'text',
				'css_classes' => 'pukka-side-meta',
			),
		)
		
	);

	return $pukka_meta_boxes;
}