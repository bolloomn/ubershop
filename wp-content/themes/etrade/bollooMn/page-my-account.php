<?php get_header(); ?>
<?php if(!is_user_logged_in()) { ?>
    <style>
        article {
            max-width: 400px;
            margin: 85px auto;

        }
    </style>
<?php }?>

			<?php if ( have_posts() ) : ?>


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>



					<div id="content" class="clearfix">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



						<div class="content-wrap">
							<div class="entry-content">

                                <?php the_content(); ?>

							</div><!-- .entry-content -->


						</div> <!-- .content-wrap -->
					</article>

					</div><!-- #content -->

				<?php endwhile; ?>

			<?php endif; ?>

<?php get_footer(); ?>