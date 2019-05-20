<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Post Types
include_once( trailingslashit( dirname( __FILE__ ) ) . 'include/custom.post.type.class.php' );

// Post Meta
include_once( trailingslashit( dirname( __FILE__ ) ) . 'util/init-testimonial-meta.php' );
include_once( trailingslashit( dirname( __FILE__ ) ) . 'util/init-portfolio-meta.php' );
//include_once( trailingslashit( dirname( __FILE__ ) ) . 'util/init-team-meta.php' );

// Taxonomies
include_once( trailingslashit( dirname( __FILE__ ) ) . 'util/init-taxonomies.php' );


add_action('init', 'pukka_register_post_types');

function pukka_register_post_types(){

	// Testimonial
	$testimonial_post = new PukkaCustomPostType(
		'testimonial', //post slug (type)
		array(
			'labels' => array(
				'name' => __( 'Testimonials', 'pukka' ),
				'singular_name' => __( 'Testimonial', 'pukka' ),
				'add_new' => __( 'Add New', 'pukka' ),
				'add_new_item' => __( 'Add New Testimonial', 'pukka' ),
				'edit' => __( 'Edit', 'pukka' ),
				'edit_item' => __( 'Edit Testimonial', 'pukka' ),
				'new_item' => __( 'New Testimonial', 'pukka' ),
				'view' => __( 'View Testimonials', 'pukka' ),
				'view_item' => __( 'View Testimonial', 'pukka' ),
				'search_items' => __( 'Search Testimonials', 'pukka' ),
				'not_found' => __( 'No Testimonials found', 'pukka' ),
				'not_found_in_trash' => __( 'No Testimonials found in Trash', 'pukka' ),
				'parent' => __( 'Parent Testimonial', 'pukka' ),
			),
			'public' => true,
			'hierarchical' => true,
			'menu_position' => 22,
			'supports' => array( 'title', 'editor', 'thumbnail', 'author' ),
			'rewrite' => array( 'slug' => 'testimonial', 'with_front' => false ),
		),
		array('testimonial_category') //taxonomies
	);

	// Portfolio
	$portfolio_post = new PukkaCustomPostType(
		'portfolio', //post slug (type)
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'pukka' ),
				'singular_name' => __( 'Portfolio', 'pukka' ),
				'add_new' => __( 'Add New', 'pukka' ),
				'add_new_item' => __( 'Add New Portfolio Item', 'pukka' ),
				'edit' => __( 'Edit', 'pukka' ),
				'edit_item' => __( 'Edit Portfolio Item', 'pukka' ),
				'new_item' => __( 'New Portfolio Item', 'pukka' ),
				'view' => __( 'View Portfolio Item', 'pukka' ),
				'view_item' => __( 'View Portfolio', 'pukka' ),
				'search_items' => __( 'Search Portfolio Items', 'pukka' ),
				'not_found' => __( 'No Portfolio Items found', 'pukka' ),
				'not_found_in_trash' => __( 'No Portfolio Items found in Trash', 'pukka' ),
				'parent' => __( 'Parent Portfolio Item', 'pukka' ),
			),
			'public' => true,
			'hierarchical' => true,
			'menu_position' => 23,
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author' ),
			'rewrite' => array( 'slug' => 'portfolio', 'with_front' => false ),
		),
		array('portfolio_category') //taxonomies
	);
/*
	// Team
	$team_post = new PukkaCustomPostType(
		'team', //post slug (type)
		array(
			'labels' => array(
				'name' => __( 'Team', 'pukka' ),
				'singular_name' => __( 'Team member', 'pukka' ),
				'add_new' => __( 'Add New', 'pukka' ),
				'add_new_item' => __( 'Add New Team member', 'pukka' ),
				'edit' => __( 'Edit', 'pukka' ),
				'edit_item' => __( 'Edit Team member', 'pukka' ),
				'new_item' => __( 'New Team member', 'pukka' ),
				'view' => __( 'View Team member', 'pukka' ),
				'view_item' => __( 'View Team', 'pukka' ),
				'search_items' => __( 'Search Team members', 'pukka' ),
				'not_found' => __( 'No Team members found', 'pukka' ),
				'not_found_in_trash' => __( 'No Team members found in Trash', 'pukka' ),
				'parent' => __( 'Parent Team member', 'pukka' ),
			),
			'public' => true,
			'hierarchical' => true,
			'menu_position' => 24,
			'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'page-attributes' ),
			'rewrite' => array( 'slug' => 'team', 'with_front' => false ),
		),
		array('team_category') //taxonomies
	);
*/
}


// Add meta boxes
if( class_exists('PukkaMetaBox') ){
	$pukka_meta_boxes = array();
	$pukka_meta_boxes = apply_filters('pukka_add_meta_boxes', $pukka_meta_boxes);

	$meta_box = new PukkaMetaBox($pukka_meta_boxes);
}