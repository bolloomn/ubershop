<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */
?>

<?php
	$blog_layout_type    = get_theme_mod( 'blog_layout_type', solosshopy_theme()->customizer->get_default( 'blog_layout_type' ) );
	$blog_featured_image = get_theme_mod( 'blog_featured_image', solosshopy_theme()->customizer->get_default( 'blog_featured_image' ) );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php if ( 'default' == $blog_layout_type && 'small' !== $blog_featured_image ) : ?>
		<div class="posts-list__left-col"><?php
			solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-date' );

		?></div><!-- .posts-list__left-col -->
	<?php endif; ?>

	<div class="posts-list__right-col">

		<?php
			if ( 'default' !== $blog_layout_type || solosshopy_check_for_small_image_default_listing() ) :
				solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-date' );
			endif;
		?>

		<div class="posts-list__item-content">

			<header class="entry-header"><?php
				solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-author' );
				solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-categories' );
				solosshopy_get_template_part( 'template-parts/post/post-components/post-title' );
			?></header><!-- .entry-header -->

			<div class="post-featured-content"><?php
				do_action( 'cherry_post_format_audio' );
				solosshopy_get_template_part( 'template-parts/post/post-components/post-sticky' );
			?></div><!-- .post-featured-content -->

			<div class="entry-content"><?php
				solosshopy_get_template_part( 'template-parts/post/post-components/post-content' );
			?></div><!-- .entry-content -->

			<footer class="entry-footer">
				<div class="entry-meta-container"><?php
					solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-tags' );
					solosshopy_get_template_part( 'template-parts/post/post-meta/content-meta-comments' );
				?></div>

				<div class="entry-footer-bottom"><?php
					solosshopy_get_template_part( 'template-parts/post/post-components/post-button' );

				?></div><!-- .entry-footer-bottom -->
			</footer><!-- .entry-footer -->
		</div><!-- .posts-list__item-content -->
	</div><!-- .posts-list__right-col -->

</article><!-- #post-## -->
