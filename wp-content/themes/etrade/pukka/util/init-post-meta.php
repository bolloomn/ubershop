<?php if ( ! defined('PUKKA_VERSION')) exit('No direct script access allowed');

add_filter('pukka_add_meta_boxes', 'pukka_post_metaboxes', 1, 1);
function pukka_post_metaboxes($pukka_meta_boxes){
	// Post meta boxes

	$pukka_meta_boxes[] = array(
		'id' => 'meta_box_formats',
		'title' => 'Post Formats',
		'post_type' => 'post',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'title' => __('Media URL', 'pukka'),
				'desc' => __('Youtube, vimeo, dailymotion, soundcloud.. <br /> (complete list: http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F)', 'pukka'),
				'id' => PUKKA_POSTMETA_PREFIX . 'media_url',
				'type' => 'text',
				'std' => ''
			),
			
			array(
				'title' => __('Media embed code', 'pukka'),
				'desc' => __('Enter here embed code from any other media service. Width should be set to 615px. <br />(For YouTube put <b>?wmode=transparent</b> after youtube link, 
					e.g. http://www.youtube.com/watch?v=cZUiMixMMz<b>?wmode=transparent</b>)', 'pukka'),
				'id' => PUKKA_POSTMETA_PREFIX . 'media_embed',
				'type' => 'textarea',
				'std' => ''
			),
			
			array(
				'title' => __('Link', 'pukka'),
				'desc' => __('Insert the URL you wish to link to.', 'pukka'),
				'id' => PUKKA_POSTMETA_PREFIX . 'link',
				'type' => 'text',
				'std' => ''
			),
		)
	);

	return $pukka_meta_boxes;
}