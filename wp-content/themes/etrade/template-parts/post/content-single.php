<?php
/**
 * Template part for displaying single posts.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php solosshopy_ads_post_before_content() ?>

	<?php $utility = solosshopy_utility()->utility; ?>

	<div class="post__left-col"><?php
		solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-date' );
	?></div><!-- .post__left-col -->

	<div class="post__right-col">
		<header class="entry-header"><?php
			solosshopy_get_template_part( 'template-parts/post/post-components/post-title' );
			solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-author' );
			solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-categories' );

			do_action( 'cherry_trend_posts_display_views' );
		?></header><!-- .entry-header -->

		<figure class="post-thumbnail"><?php
			$utility->media->get_image( array(
				'size'        => 'solosshopy-thumb-l',
				'mobile_size' => 'solosshopy-thumb-l',
				'html'        => '<img class="wp-post-image" src="%3$s" alt="%4$s">',
				'placeholder' => false,
				'echo'        => true,
			) );
		?></figure><!-- .post-thumbnail -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links__title">' . esc_html__( 'Pages:', 'solosshopy' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span class="page-links__item">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'solosshopy' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<div class="entry-meta-container">
				<div class="entry-meta entry-meta--left"><?php
					solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-tags' );
				?></div>

			</div>
			<?php do_action( 'cherry_trend_posts_display_rating' ); ?>
		</footer><!-- .entry-footer -->

	</div><!-- .post__right-col -->

</article><!-- #post-## -->
