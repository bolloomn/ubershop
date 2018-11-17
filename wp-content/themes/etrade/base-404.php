<?php
/**
 * The base template for displaying 404 pages (not found).
 *
 * @package Solosshopy
 */
?>
<?php get_header( solosshopy_template_base() ); ?>

	<?php solosshopy_site_breadcrumbs(); ?>

	<div <?php solosshopy_content_wrap_class(); ?>>

		<div class="row">

			<div id="primary" <?php solosshopy_primary_content_class(); ?>>

				<main id="main" class="site-main" role="main">

					<?php include solosshopy_template_path(); ?>

				</main><!-- #main -->

			</div><!-- #primary -->

			<?php get_sidebar(); // Loads the sidebar.php. ?>

		</div><!-- .row -->

	</div><!-- .site-content_wrap -->

<?php get_footer( solosshopy_template_base() ); ?>
