<?php get_header(); ?>

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
					<article style="background: none !important;" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">

                            <div class="row">

                                <div class="col-lg-8 offset-lg-2 ">
                                    <div class="text-center mb-4">
                                    <h1 style="color:#232f3e; font-weight: 400;"><?php the_title(); ?></h1>
                                    </div>
                                    <div class="bg-white  p-4 mb-4">
                                        <?php if("on" != get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX . 'hide_page_title', true)) { ?>
                                            <h1 class="entry-title"><?php the_title(); ?></h1>
                                        <?php } ?>
                                        <div class="entry-content">
                                            <?php the_content(); ?>
                                            <?php wp_link_pages(); ?>
                                        </div><!-- .entry-content -->

                                        <?php pukka_after_content(); ?>

                                    </div> <!-- .content-wrap -->
                                </div>
                            </div>


					</article>

					</div><!-- #content -->

				<?php endwhile; ?>

			<?php endif; ?>

<?php get_footer(); ?>