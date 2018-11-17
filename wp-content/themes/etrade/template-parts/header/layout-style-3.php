<?php
/**
 * Template part for style-3 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */

$vertical_menu_slide             = ( ! is_rtl() ) ? 'right' : 'left';
$header_contact_block_visibility = get_theme_mod( 'header_contact_block_visibility', solosshopy_theme()->customizer->get_default( 'header_contact_block_visibility' ) );
$header_btn_visibility           = get_theme_mod( 'header_btn_visibility', solosshopy_theme()->customizer->get_default( 'header_btn_visibility' ) );
$search_visible                  = get_theme_mod( 'header_search', solosshopy_theme()->customizer->get_default( 'header_search' ) );
$header_cart             = get_theme_mod( 'header_cart', solosshopy_theme()->customizer->get_default( 'header_cart' ) );

?>
<div class="header-container_wrap container">
	<?php solosshopy_vertical_main_menu( $vertical_menu_slide ); ?>

	<?php if ( $header_contact_block_visibility || $header_btn_visibility ) : ?>
		<div class="header-row__flex header-components__contact-button header-components__grid-elements"><?php
			solosshopy_contact_block( 'header' );
			solosshopy_header_btn();
		?></div>
	<?php endif; ?>

	<div class="header-container__flex-wrap">
		<div class="header-container__flex">
			<div class="site-branding">
				<?php solosshopy_header_logo() ?>
				<?php solosshopy_site_description(); ?>
			</div>

			<div class="header-components header-components__search-cart"><?php
				solosshopy_header_search_toggle();
				solosshopy_header_woo_cart();
				solosshopy_vertical_menu_toggle( 'main-menu' );
			?></div>
		</div>

		<?php solosshopy_header_search( '<div class="header-search">%s<span class="search-form__close"></span></div>' ); ?>
	</div>
</div>