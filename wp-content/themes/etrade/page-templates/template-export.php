<?php
/*
*	Template Name: Options Export
*/


$options = array(
    // wp widgets
    'widget_categories',
    'widget_text',
    'widget_search',
    'widget_recent-posts',
    'widget_recent-comments',
    'widget_archives',
    'widget_meta',
    'widget_tag_cloud',

    // woo widgets
    'widget_woocommerce_layered_nav',
    'widget_woocommerce_price_filter',
    'widget_woocommerce_products',
    'widget_woocommerce_product_tag_cloud',
    'widget_woocommerce_recent_reviews',
    'widget_woocommerce_widget_cart',
    'widget_woocommerce_layered_nav_filters',
    'widget_woocommerce_product_categories',
    'widget_woocommerce_product_search',
    'widget_woocommerce_recently_viewed_products',
    'widget_woocommerce_top_rated_products',

    // pukka widgets
    'widget_pukka-testimonials-widget',
    'widget_pukka-fa-widget',
    'widget_pukka-ad-widget',
    'widget_pukka-banner-widget',
    'widget_pukka_products_list',
    'widget_pukka-latest-posts-widget',
    'widget_pukka-featured-post-widget',
    'widget_pukka-facebook-widget',

    // other widgets
    'widget_ninja_forms_widget',

	'sidebars_widgets',
	PUKKA_OPTIONS_NAME
);

$wp_uploads = wp_upload_dir();
$file_path = $wp_uploads['basedir'] . '/pukka_data.php';
$file = fopen($file_path, 'w');

if(!$file){ die('File could not be created.'); }

$out = '<?php' . "\n";
$out .= 'global $pukka_theme_options;' . "\n";
// first to fetch all options and widgets and stuff
$out .= '$pukka_theme_options = array(' . "\n";
foreach($options as $key => $val){
	$option = get_option($val);
	$str = json_encode($option);
	$str64 = base64_encode($str);
	$out .= "'$val' => '" . $str64 . "',\n";
}
$out .= ");\n\n";
// write it down to file
fwrite($file, $out);

//and now to fetch dynamic meta data

//first fetch all the post
$args = array(
				'post_type' => array('post', 'page'), //only post and pages can have dynamic meta fields
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);
$posts = get_posts($args);

$out = 'global $pukka_dynamic_meta_data;' . "\n";
$out .= '$pukka_dynamic_meta_data = array(' . "\n";
foreach($posts as $post){
	$meta = get_post_meta($post->ID, '_pukka_dynamic_meta_box', true);
	if(!empty($meta)){
		$data = json_encode($meta);
		$data = base64_encode($data);
		$out .= '"' . $post->ID . '"' . ' => "' . $data . '", ' . "\n";
	}
}
$out .= ');' . "\n";
fwrite($file, $out);
fclose($file);

echo '<textarea>';
echo $file_path . "\n\n";
$theme = wp_get_theme();
$theme_mods = get_option('theme_mods_' . strtolower($theme->get('Name')));
print_r($theme_mods);
echo '</textarea>';