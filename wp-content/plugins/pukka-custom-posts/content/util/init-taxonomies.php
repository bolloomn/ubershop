<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Just registring taxonomies that we're going to use
add_action('init', 'pukka_register_taxonomies');

function pukka_register_taxonomies(){

	// Testimonial
	$labels = array(
			'name' => __( 'Testimonials Categories', 'pukka' ),
			'singular_name' => __( 'Testimonial Category', 'pukka' ),
			'search_items' =>  __( 'Search Testimonials Categories','pukka' ),
			'all_items' => __( 'All Testimonials Categories','pukka' ),
			'parent_item' => __( 'Parent Testimonial Category','pukka' ),
			'parent_item_colon' => __( 'Parent Testimonial Category:','pukka' ),
			'edit_item' => __( 'Edit Testimonials Category','pukka' ), 
			'update_item' => __( 'Update Testimonials Category','pukka' ),
			'add_new_item' => __( 'Add New Testimonials Category','pukka' ),
			'new_item_name' => __( 'New Testimonials Category Name','pukka' ),
			'menu_name' => __( 'Testimonials Categories','pukka' ),
	);

	register_taxonomy(
				'testimonial_category',
				null, // we'll register post type later
				array(
					'hierarchical' => true,
					'labels' => $labels,
					'rewrite' => array('slug' => 'testimonial-category')
				)
			);

	// Portofio
	$labels = array(
			'name' => __( 'Portfolio Categories', 'pukka' ),
			'singular_name' => __( 'Portfolio Category', 'pukka' ),
			'search_items' =>  __( 'Search Portfolio Categories','pukka' ),
			'all_items' => __( 'All Portfolio Categories','pukka' ),
			'parent_item' => __( 'Parent Portfolio Category','pukka' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:','pukka' ),
			'edit_item' => __( 'Edit Portfolio Category','pukka' ), 
			'update_item' => __( 'Update Portfolio Category','pukka' ),
			'add_new_item' => __( 'Add New Portfolio Category','pukka' ),
			'new_item_name' => __( 'New Portfolio Category Name','pukka' ),
			'menu_name' => __( 'Portfolio Categories','pukka' ),
	);

	register_taxonomy(
				'portfolio_category',
				null, // we'll register post type later
				array(
					'hierarchical' => true,
					'labels' => $labels,
					'rewrite' => array('slug' => 'portfolio-category')
				)
			);

	// Team
	$labels = array(
			'name' => __( 'Team Categories', 'pukka' ),
			'singular_name' => __( 'Team Category', 'pukka' ),
			'search_items' =>  __( 'Search Team Categories','pukka' ),
			'all_items' => __( 'All Team Categories','pukka' ),
			'parent_item' => __( 'Parent Team Category','pukka' ),
			'parent_item_colon' => __( 'Parent Team Category:','pukka' ),
			'edit_item' => __( 'Edit Team Category','pukka' ), 
			'update_item' => __( 'Update Team Category','pukka' ),
			'add_new_item' => __( 'Add New Team Category','pukka' ),
			'new_item_name' => __( 'New Team Category Name','pukka' ),
			'menu_name' => __( 'Team Categories','pukka' ),
	);

	register_taxonomy(
				'team_category',
				null, // we'll register post type later
				array(
					'hierarchical' => true,
					'labels' => $labels,
					'rewrite' => array('slug' => 'team-category')
				)
			);
}