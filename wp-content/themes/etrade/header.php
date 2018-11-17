<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Solosshopy
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php solosshopy_get_page_preloader(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'solosshopy' ); ?></a>
	<header id="masthead" <?php solosshopy_header_class(); ?> role="banner">
        <?php

            $header_layout_type = get_theme_mod( 'header_layout_type', solosshopy_theme()->customizer->get_default( 'header_layout_type' ) );

            solosshopy_ads_header();
            solosshopy_get_template_part( 'template-parts/header/mobile-panel' );
            solosshopy_get_template_part( 'template-parts/header/top-panel', esc_attr( $header_layout_type ) );

        ?>

		<div <?php solosshopy_header_container_class(); ?>>
			<?php
                solosshopy_get_template_part( 'template-parts/header/layout', esc_attr( $header_layout_type ) );

            ?>
		</div><!-- .header-container -->
	</header><!-- #masthead -->

	<div id="content" <?php solosshopy_content_class(); ?>>
