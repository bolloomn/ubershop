<?php  
get_header();

	// get posts column number
	$posts_col_no = pukka_get_option('posts_col_no') != '' ? (int)pukka_get_option('posts_col_no') : 1;
	$grid_enabled = $posts_col_no > 1 ? true : false;

	// check if we have posts
	if( have_posts() ) :

		// open grid wrapper if grid is enabled
		if( $grid_enabled ) : ?>
			<div class="brick-wrap column-<?php echo $posts_col_no; ?>">
		<?php
		endif;

		/* Start the Loop */ 
		while ( have_posts() ) : the_post();

			if($grid_enabled){
				get_template_part( 'content-grid', get_post_format() );
			}
			else{
				get_template_part( 'content', get_post_format() );
			}

		endwhile;

		// close grid wrapper if grid is enabled
		if( $grid_enabled ) : ?>
			</div><!-- .brick-wrap -->
		<?php endif;

		// page navigation
		pukka_paging_nav();

	else :
		get_template_part( 'content', 'none' );
	endif; // if ( have_posts() )

get_footer();