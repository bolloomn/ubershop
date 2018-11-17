<?php
/**
 * The template for displaying the style-3 footer layout.
 *
 * @package Solosshopy
 */
?>

<div <?php solosshopy_footer_container_class(); ?>>
	<div class="site-info container-wide">
		<div class="site-info-wrap">
			<div class="site-info-block"><?php
				solosshopy_footer_logo();
				solosshopy_footer_copyright();
			?></div>
			<?php solosshopy_footer_menu(); ?>
			<div class="site-info-block"><?php
				solosshopy_contact_block( 'footer' );
				solosshopy_social_list( 'footer' );
			?></div>
		</div>
	</div><!-- .site-info -->
</div><!-- .container -->
