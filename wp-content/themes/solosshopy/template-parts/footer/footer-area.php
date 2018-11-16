<?php
/**
 * The template for displaying footer widget area.
 *
 * @package Solosshopy
 */
// Check footer-area visibility.
if ( ! solosshopy_get_mod( 'footer_widget_area_visibility', true, 'esc_attr' ) ) {
	return;
} ?>

<div <?php echo solosshopy_get_html_attr_class( array( 'footer-area-wrap' ), 'footer_widgets_bg' ); ?>>
	<div class="container">
		<?php do_action( 'solosshopy_render_widget_area', 'footer-area' ); ?>
	</div>
</div>
