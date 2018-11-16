<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Solosshopy
 */

$page_404_image     = get_theme_mod( 'page_404_image', solosshopy_theme()->customizer->get_default( 'page_404_image' ) );
$btn_style_preset   = get_theme_mod( 'page_404_btn_style_preset', solosshopy_theme()->customizer->get_default( 'page_404_btn_style_preset' ) );
$text_color         = get_theme_mod( 'page_404_text_color', solosshopy_theme()->customizer->get_default( 'page_404_text_color' ) );
$additional_class   = ( 'light' === $text_color ) ? 'invert' : 'regular';
$page_404_image_url = '../assets/images/404.jpg';

if ( $page_404_image ) {
	$page_404_image_url = esc_url( solosshopy_render_theme_url( $page_404_image ) );
	$page_404_image_url = '<img src="' . $page_404_image_url . '">';
}
?>
<section class="error-404 not-found <?php echo esc_attr( $additional_class ); ?>">
	<header class="page-header">
		<h1 class="page-title screen-reader-text"><?php esc_html_e( '404', 'solosshopy' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<div class="row">
			<div class="col-xs-12 col-md-6 image-404"><?php
				echo wp_kses_post( $page_404_image_url );
			?></div>
			<div class="col-xs-12 col-md-6">
				<h4 class="title"><?php esc_html_e( 'Page Not Found.', 'solosshopy' ); ?></h4>
				<p><?php esc_html_e( 'Unfortunately the page you were looking for could not be found.', 'solosshopy' ); ?></p>
				<p><a class="btn btn-<?php echo sanitize_html_class( $btn_style_preset ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to home!', 'solosshopy' ); ?></a></p>
			</div>
		</div>

	</div><!-- .page-content -->
</section><!-- .error-404 -->
