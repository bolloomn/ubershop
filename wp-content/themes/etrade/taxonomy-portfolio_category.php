<?php 
get_header();

	// get portfolio  column number
	$posts_col_no = pukka_get_option('portfolio_col_no') != '' ? (int)pukka_get_option('portfolio_col_no') : 2;

	$css_classes = 'column-'. pukka_get_option('portfolio_col_no');

	// check if we have posts
	if( have_posts() ) : ?>

		<header class="archive-header clearfix">
			<?php if( function_exists('woocommerce_breadcrumb') ){ woocommerce_breadcrumb(); } ?>
			<!--
			<h1 class="archive-title"><?php echo single_cat_title( '', false ); ?></h1>
			-->
			<?php if ( term_description() ) : // Show optional term description ?>
			<div class="archive-meta"><?php echo term_description(); ?></div>
			<?php endif; ?>
		</header><!-- .archive-header -->

		<div class="column-wrap clearfix <?php echo $css_classes; ?>">

		<?php
			/* Start the Loop */ 
			while ( have_posts() ) : the_post();

				if( pukka_get_option('portfolio_no_margins') == 'on' ){
					get_template_part( 'loop/content-portfolio', 'no-margin');
				}
				else{
					get_template_part( 'loop/content-portfolio');
				}

			endwhile;

			// page navigation
			pukka_paging_nav(); ?>

		</div><!-- .column-wrap -->

		<?php

		else : 
			get_template_part( 'content', 'none' );
		endif; // if ( have_posts() )


get_footer();