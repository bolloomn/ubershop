<?php get_header(); ?>

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if( function_exists('woocommerce_breadcrumb') ){ woocommerce_breadcrumb(); } ?>
					<div id="content" class="clearfix">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php /* Print article media */ ?>
						<?php if( in_array(get_post_format(), array('video', 'audio', 'gallery', 'link')) || has_post_thumbnail() ) : ?>
							<div class="featured">
							<?php
								if( has_post_format('video') || has_post_format('audio') ){
									// print video/sound media
									pukka_media();
								}
								elseif( has_post_format('gallery') ){
									// create gallery
									$args = array(
										'post_type' => 'attachment',
										'numberposts' => -1,
										'post_status' => null,
										'post_parent' => $post->ID,
										'exclude' => get_post_thumbnail_id(), // dont display featured image
									);

									$attachments = get_posts($args);

									if( $attachments ){
										echo '<div class="slider">';
										echo '<ul class="slides gallery-preview">';
										$thumb_list = '';
										foreach ( $attachments as $attachment ) {
											$thumb_list .= '<li>';

											$img = wp_get_attachment_image_src($attachment->ID, 'thumb-post');
											$thumb_list .= '<img src="'.  $img[0] .'" width="'. $img[1] .'" height="'. $img[2] .'" alt="'. esc_attr($attachment->post_title) .'"/>';
											$thumb_list .= '</li>' ."\n";
										}

										echo $thumb_list;

										echo '</ul> <!-- .slides -->';
										echo '</div> <!-- .slider -->';
									}
								}
								elseif( has_post_format('link') ) {
									// Print article image and link to external URL
									echo '<a href="'. get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'link', true) .'" target="_blank">';
									the_post_thumbnail('thumb-post');
									echo '</a>';
								}
								elseif( has_post_thumbnail() ){
									// Just post thumbnail
									the_post_thumbnail('thumb-post');
								}
							?>
							</div> <!-- .featured -->
						<?php endif; // if( has_post_format(array('video', 'audio', 'gallery')) || has_post_thumbnail() ) : ?>

						<div class="content-wrap">
							<?php if( !has_post_format('quote') ) : ?>
							<header class="entry-header headings">
								<div class="entry-meta">
									<?php pukka_entry_meta(); ?>
								</div> <!-- .entry-meta -->
							</header> <!-- .entry-header -->

							<h1 class="entry-title"><?php the_title(); ?></h1>
							<?php endif; ?>

								<div class="entry-content">
									<?php the_content(); ?>
									<?php wp_link_pages(); ?>
								</div><!-- .entry-content -->

								<?php pukka_after_content(); ?>
							</div> <!-- .content-wrap -->
					</article>

					<?php comments_template(); ?>

					</div><!-- #content -->

				<?php endwhile; ?>

			<?php endif; ?>

<?php get_footer(); ?>