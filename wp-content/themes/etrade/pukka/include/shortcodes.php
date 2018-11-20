<?php
/**
* This file holds shortcodes used in this theme. It is independant from theme functions, so it can be used in other themes (ie when theme is changed).
* In that case here's migration instruction:
* 1) replace PUKKA_POSTMETA_PREFIX with value defined in theme_folder/pukka/init.php file (most likely that is '_pukka_')
* 2) template files for portofolio and team are located in theme_folder/loop folder
* 3) if you want to use testimonial shortcode you'll have to enqueue 'flexslider2' script (http://flexslider.woothemes.com/).
*    initilization can be copied from theme_folder/js/pukka.js
*/

function pukka_shortcode_testimonials( $atts, $content = null ) {
	global $post;
	extract(shortcode_atts(array(
				'number' => 5,
				// 'cat_id' => '',
				'hide_testimonial_title' => '',
				'show_image' => 'on',
				'show_author' => 'on',
				'show_website' => 'on',
				'cat_slugs' => '',
	), $atts));

	$html = '';

	$query_args = array(
				'posts_per_page' => $number,
				'post_type' => 'testimonial',
				'post_status' => 'publish',
			);

	if( !empty($cat_slugs) ){

		$cat_slugs = explode(',', $cat_slugs);

		$query_args['tax_query'] = array(
								array(
									'taxonomy' => 'testimonial_category',
									'field' => 'slug',
									'terms' => $cat_slugs,
								)
							);
	}

	$query = new WP_Query($query_args);

	if( $query->have_posts() ){
		$html .= '<div class="testimonials-slider testimonials-wrap">' ."\n";
		$html .= '<ul class="slides">' ."\n";
		while( $query->have_posts() ) : $query->the_post();

			$html .= '<li>' ."\n";

			if( $hide_testimonial_title != 'on' ){
				$html .= '<h4 class="testimonial-title">'. get_the_title() .'</h4>' ."\n";
			}

			if( $show_image == 'on' && has_post_thumbnail() ){
				$html .= '<span class="testimonial-thumb">'. get_the_post_thumbnail($post->ID, 'full').'</span>' ."\n";
			}

			$html .= '<p>'. get_the_content() .'</p>'. "\n";

			$author_meta = '';
			if( $show_author == 'on' && get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'author' , true) != '' ){
				$author_meta .= '<span class="testimonial-author">'. get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'author', true) .'</span>';
			}

			if( $show_website == 'on' && get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'website' , true) != '' ){
				if( $author_meta != '' ){
					$author_meta .= ', ';
				}

				$author_meta .= '<a href="'. get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'website', true) .'" target="_blank" class="testimonial-website">'. preg_replace('#^https?://#', '', get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'website', true)) .'</a>';
			}

			if( $author_meta != '' ){

				$html .= '<span class="testimonial-meta">' ."\n";
				$html .=  $author_meta;
				$html .=  '</span>' ."\n";;
			}
			

			$html .= '</li>' ."\n";

		endwhile;
		$html .= '</ul>' ."\n";
		$html .= '</div> <!-- testimonials-slider -->' ."\n";
	} // if( $query->have_posts() )

	wp_reset_postdata();

	return $html;
}
add_shortcode('testimonials', 'pukka_shortcode_testimonials');


function pukka_shortcode_portfolio( $atts, $content = null ) {
	global $post;

	extract(shortcode_atts(array(
				'number' => 5, // number of posts
				'ids' => '', // comma separated post ids
				'cat_slugs' => '', // comma separated category slugs
				'no_margins' => true,
				'columns' => 3,
	), $atts));

	$no_margins = ($no_margins == 'on') ? true : false;

	$html = '';

	// build query
	$query_args = array(
				'posts_per_page' => $number,
				'post_type' => 'portfolio',
				'post_status' => 'publish',
			);

	if( !empty($cat_slugs) ){

		$cat_slugs = explode(',', $cat_slugs);

		$query_args['tax_query'] = array(
								array(
									'taxonomy' => 'portfolio_category',
									'field' => 'slug',
									'terms' => $cat_slugs,
								)
							);
	}

	if( !empty($ids) ){
		$ids = explode(',', $ids);

		$query_args['post__in'] = $ids;
		$query_args['orderby'] = 'post__in';
		$query_args['order'] = 'ASC';
	}

	$css_classes = 'column-'. $columns;

	$query = new WP_Query($query_args);

	if( $query->have_posts() ){

		$html .= '<div class="column-wrap clearfix '. $css_classes .'">' ."\n";

		ob_start();

		while( $query->have_posts() ) : $query->the_post();

			if( $no_margins ){
				get_template_part( 'loop/content-portfolio', 'no-margin');
			}
			else{
				get_template_part( 'loop/content-portfolio');
			}

		endwhile;

		$html .= ob_get_clean();

		$html .= '</div><!-- .column -->' ."\n";
	} // if( $query->have_posts() )

	wp_reset_postdata();

	return $html;
}
add_shortcode('portfolio', 'pukka_shortcode_portfolio');

/*
function pukka_shortcode_team( $atts, $content = null ) {
	global $post;

	extract(shortcode_atts(array(
				'number' => 5, // number of posts
				'ids' => '', // comma separated post ids
				'cat_slugs' => '', // comma separated category slugs
				'no_margins' => true,
				'columns' => 3,
	), $atts));

	$no_margins = ($no_margins == 'on') ? true : false;

	$html = '';

	// build query
	$query_args = array(
				'posts_per_page' => $number,
				'post_type' => 'team',
				'post_status' => 'publish',
				'orderby' => '',
				'order' => 'ASC',
			);

	if( !empty($cat_slugs) ){

		$cat_slugs = explode(',', $cat_slugs);

		$query_args['tax_query'] = array(
								array(
									'taxonomy' => 'team_category',
									'field' => 'slug',
									'terms' => $cat_slugs,
								)
							);
	}

	if( !empty($ids) ){
		$ids = explode(',', $ids);

		$query_args['post__in'] = $ids;
		$query_args['orderby'] = 'post__in';
		$query_args['order'] = 'ASC';
	}

	// Order arguments
	if( !empty($orderby) ){
		$query_args['orderby'] = $orderby;
		$query_args['order'] = $order;
	}

	$css_classes = 'column-'. $columns;

	$query = new WP_Query($query_args);

	if( $query->have_posts() ){

		$html .= '<div class="column-wrap clearfix '. $css_classes .'">' ."\n";

		ob_start();

		while( $query->have_posts() ) : $query->the_post();

			if( $no_margins ){
				get_template_part( 'loop/content-team', 'no-margin');
			}
			else{
				get_template_part( 'loop/content-team');
			}

		endwhile;

		$html .= ob_get_clean();

		$html .= '</div><!-- .column -->' ."\n";
	} // if( $query->have_posts() )

	wp_reset_postdata();

	return $html;
}
add_shortcode('team', 'pukka_shortcode_team');
*/

function pukka_shortcode_message_box( $atts, $content = null ) {

	if( $content == null ){
		return;
	}

	extract(shortcode_atts(array(
				'icon' => '',
				'bg_color' => '',
				// 'text_color' => '',
	), $atts));

	$message_style = '';

	if( !empty($bg_color) ){
		$message_style .= 'background-color: '. $bg_color;
	}

	$html = '';

	$html .= '<div class="pukka-message-box" style="'. $message_style .'">' ."\n";
	$html .= '<div class="pukka-message-box-inner">';

	if( $icon != '' ){
		$html .= '<span class="pukka-message-box-icon-holder"><i class="fa '. $icon .'"></i></span>';
	}
	$html .= '<span class="pukka-message-box-text-holder">'. $content .'</span>';

	// close link
	$html .= '<a href="#" class="close"><i class="fa fa-times"></i></a>';

	$html .= '</div> <!--  .pukka-message-box-inner -->' ."\n";
	$html .= '</div> <!--  .pukka-message-box -->' ."\n";


	return $html;

}
add_shortcode('message_box', 'pukka_shortcode_message_box');

