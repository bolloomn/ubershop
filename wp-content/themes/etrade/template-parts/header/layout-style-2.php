<?php
/**
 * Template part for style-2 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */

$search_visible        = get_theme_mod( 'header_search', solosshopy_theme()->customizer->get_default( 'header_search' ) );
$header_cart           = get_theme_mod( 'header_cart', solosshopy_theme()->customizer->get_default( 'header_cart' ) );
?>
<div class="header-container_wrap container">

	<div class="header-row__flex">
		<div class="site-branding">
			<?php solosshopy_header_logo() ?>
			<?php solosshopy_site_description(); ?>
		</div>

		<div class="header-row__flex header-components__contact-button"><?php
			solosshopy_contact_block( 'header' );
			solosshopy_header_btn();
		?></div>
	</div>

	<div class="header-nav-wrapper">
		<?php solosshopy_main_menu(); ?>

		<?php if ( $search_visible || $header_cart ) : ?>
			<div class="header-components header-components__search-cart"><?php
				solosshopy_header_search_toggle();
				solosshopy_header_woo_cart();
			?></div>
		<?php endif; ?>

		<?php solosshopy_header_search( '<div class="header-search">%s<span class="search-form__close"></span></div>' ); ?>
	</div>

</div>
