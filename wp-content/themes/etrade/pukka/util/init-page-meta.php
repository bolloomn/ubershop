<?php if ( ! defined('PUKKA_VERSION')) exit('No direct script access allowed');

add_filter('pukka_add_meta_boxes', 'pukka_page_metaboxes', 1, 1);
function pukka_page_metaboxes($pukka_meta_boxes){
	// Post meta boxes
	$pukka_meta_boxes[] = array(
		'id' => 'post_meta',
		'title' => 'Page Options',
		'post_type' => 'page',
		'context' => 'side',
		'priority' => 'high',

		'fields' => array(
			array(
				'title' => __('Hide page title', 'pukka'),
				'desc' => __('Select this feature to hide page title.', 'pukka'),
				'type' => 'checkbox',
				'id' => PUKKA_POSTMETA_PREFIX . 'hide_page_title',
			),
			array(
				'title' => 'Page Width',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'page_width',
				'type' => 'select',
				'no_default_text' => true,
				'css_classes' => 'pukka-side-meta',
				'options' => array('default' => 'Default', 'boxed' => 'Boxed', 'full' => 'Full width' )
			),
			array(
				'title' => __('Overwrite default sidebars', 'pukka'),
				'desc' => __('Select this feature to enable custom sidebars on this page.', 'pukka'),
				'type' => 'checkbox',
				'id' => PUKKA_POSTMETA_PREFIX . 'overwrite_sidebars',
			),
			array(
				'title' => 'Left Sidebar',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'page_left_sidebar_id',
				'type' => 'select',
				'subtype' => 'widget_areas',
				'css_classes' => 'pukka-side-meta',
			),
			array(
				'title' => 'Right Sidebar',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'page_right_sidebar_id',
				'type' => 'select',
				'subtype' => 'widget_areas',
				'css_classes' => 'pukka-side-meta',
			),
			array(
				'title' => 'Enable social buttons?',
				'desc' => '',
				'id' => PUKKA_POSTMETA_PREFIX . 'enable_share',
				'type' => 'checkbox',
			),
		)
	);

	return $pukka_meta_boxes;
}