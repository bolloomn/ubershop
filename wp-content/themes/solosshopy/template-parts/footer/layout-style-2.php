<?php
/**
 * The template for displaying the style-2 footer layout.
 *
 * @package Solosshopy
 */
?>

<div <?php solosshopy_footer_container_class(); ?>>
	<div class="site-info container"><?php
		solosshopy_footer_logo();
		solosshopy_footer_menu();
		solosshopy_contact_block( 'footer' );
		solosshopy_social_list( 'footer' );
		solosshopy_footer_copyright();
	?></div><!-- .site-info -->
</div><!-- .container -->
