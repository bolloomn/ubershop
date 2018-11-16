<?php get_header(); ?>

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if( function_exists('woocommerce_breadcrumb') ){ woocommerce_breadcrumb(); } ?>
					<div id="content" class="clearfix">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php /* Print article media */ ?>
						<?php if( has_post_thumbnail() ) : ?>
							<div class="featured">
							<?php the_post_thumbnail('thumb-post'); ?>
							</div> <!-- .featured -->
						<?php endif; // if( has_post_thumbnail() ) : ?>

						<div class="content-wrap">

							<h1 class="entry-title"><?php the_title(); ?></h1>

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