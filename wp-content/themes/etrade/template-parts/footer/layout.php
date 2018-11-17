<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Solosshopy
 */

$footer_logo_visibility    = get_theme_mod( 'footer_logo_visibility', solosshopy_theme()->customizer->get_default( 'footer_logo_visibility' ) );
$footer_menu_visibility    = get_theme_mod( 'footer_menu_visibility', solosshopy_theme()->customizer->get_default( 'footer_menu_visibility' ) );
$footer_contact_block_visibility    = get_theme_mod( 'footer_contact_block_visibility', solosshopy_theme()->customizer->get_default( 'footer_contact_block_visibility' ) );
$footer_social_links    = get_theme_mod( 'footer_social_links', solosshopy_theme()->customizer->get_default( 'footer_social_links' ) );
?>

<div <?php solosshopy_footer_container_class(); ?>>
    <?php if ( $footer_contact_block_visibility ) { ?>
		<div class="site-info container site-info-first-row">
			<div class="site-info-wrap">
                <?php solosshopy_contact_block( 'footer' ); ?>
			</div>
		</div><!-- .site-info-first-row -->
    <?php } ?>
    <?php if ( $footer_social_links ) { ?>
	<div class="site-info container site-info-second-row">
		<div class="site-info-wrap">
			<div class="site-info-block">
                    <?php solosshopy_footer_logo(); ?>
                    <?php solosshopy_footer_copyright(); ?>
            </div>
            <?php solosshopy_social_list( 'footer' ); ?>

            <?php solosshopy_footer_menu(); ?>
		</div>
	</div><!-- .site-info-second-row -->
    <?php } ?>

</div><!-- .container -->
