<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter('pukka_add_meta_boxes', 'pukka_team_metaboxes', 1, 1);
function pukka_team_metaboxes($pukka_meta_boxes){
	$pukka_meta_boxes[] = array(
		'id' => 'team_meta_1',
		'title' => 'Team info',
		'post_type' => 'team',
		'context' => 'side',
		'priority' => 'high',
		'fields' => array(
			array(
				'title' => 'Position',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'position',
				'type' => 'text',
				'css_classes' => 'pukka-side-meta',
			),
			array(
				'title' => 'Link',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'link',
				'type' => 'text',
				'css_classes' => 'pukka-side-meta',
			),
		)
		
	);

	return $pukka_meta_boxes;
}