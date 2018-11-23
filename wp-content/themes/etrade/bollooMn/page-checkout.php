<?php
/* Template Name: checkout */
if(!is_user_logged_in()) {wp_redirect('login?r=2'); }
get_header(); ?>

			<?php if ( have_posts() ) : ?>


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

				<?php
					// show breadcrumb
					if( function_exists('woocommerce_breadcrumb')
						&& "on" != get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'hide_page_title', true)
					){
						woocommerce_breadcrumb();
					}

				?>

					<div id="content" class="clearfix">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if( has_post_thumbnail() ) : ?>
							<div class="featured">
							<?php the_post_thumbnail('thumb-single'); ?>
							</div> <!-- .featured -->
						<?php endif; ?>

						<div class="content-wrap">
							<?php if("on" != get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'hide_page_title', true)) { ?>
							<h1 class="entry-title"><?php the_title(); ?></h1>
							<?php } ?>
							<div class="entry-content">
								<?php the_content(); ?>
								<?php wp_link_pages(); ?>
							</div><!-- .entry-content -->

							<?php pukka_after_content(); ?>

						</div> <!-- .content-wrap -->
					</article>

					</div><!-- #content -->

				<?php endwhile; ?>

			<?php endif; ?>

<?php get_footer(); ?>