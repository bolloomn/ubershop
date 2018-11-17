<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Solosshopy
 */
?>

	</div><!-- #content -->

	<footer id="colophon" <?php solosshopy_footer_class() ?> role="contentinfo">
        <?php
            $footer_layout_type = solosshopy_theme()->customizer->get_default( 'footer_layout_type' );

            solosshopy_get_template_part( 'template-parts/footer/footer-area' );
            solosshopy_get_template_part( 'template-parts/footer/layout', esc_attr( get_theme_mod( $footer_layout_type ) ) );
		?>
        </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
