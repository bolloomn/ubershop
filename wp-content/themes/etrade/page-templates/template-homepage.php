<?php
/*
* Template name: Home Page
*/

/*
* This is page template for the Home Page
*/
?>
<?php get_header(); ?>


<div id="content" class="clearfix">
			<?php if ( have_posts() ) : ?>


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>

						<?php pukka_after_content(); ?>
				<?php endwhile; ?>

			<?php endif; ?>
</div><!-- #content -->

<?php get_footer(); ?>