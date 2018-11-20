<?php
/*
* Template name: Portfolio
*/

/*
* This is page template for displaying portfolio posts
*/
?>
<?php get_header(); ?>

<?php
	// get portfolio  column number
	$posts_col_no = pukka_get_option('portfolio_col_no') != '' ? (int)pukka_get_option('portfolio_col_no') : 2;

	$css_classes = 'column-'. $posts_col_no;
?>

				<?php
					// show breadcrumb
					if( "on" != get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'hide_page_title', true) ){
						if( function_exists('woocommerce_breadcrumb') ){ woocommerce_breadcrumb(); }
					}
				?>

				<?php
					// Build select (filter) links

					$terms = get_terms('portfolio_category');

					if( !is_wp_error($terms) && count($terms) > 1 ) : ?>

					<div id="grid-filter">
						<span data-cat="all" class="current"><?php _e('All', 'pukka'); ?></span>
						<?php foreach( $terms as $term ) : ?>
						 | 
						<span data-cat="<?php echo $term->slug; ?>"><?php echo $term->name; ?></span>
						<?php endforeach; ?>
					</div> <!-- #grid-filter -->

				<?php endif; ?>
				
					<div id="content" class="clearfix">

						<?php if( !empty($post->post_content) ) : ?>
							<div class="content-wrap">
								<?php the_content(); ?>
							</div> <!-- .content-wrap -->
						<?php endif; ?>
					
						<div class="column-wrap portfolio-grid <?php echo $css_classes; ?>">

						<?php
							
							$args = array(
										'post_type' => 'portfolio',
										'post_status' => 'publish',
										'posts_per_page' => -1,
									);

							$query = new WP_Query($args);

							while ( $query->have_posts() ) : $query->the_post();

								if( pukka_get_option('portfolio_no_margins') == 'on' ){
									get_template_part( 'loop/content-portfolio', 'no-margin');
								}
								else{
									get_template_part( 'loop/content-portfolio');
								}

							endwhile;

							wp_reset_postdata();
							?>

						</div><!-- .column-wrap -->

					</div><!-- #content -->

<?php get_footer(); ?>