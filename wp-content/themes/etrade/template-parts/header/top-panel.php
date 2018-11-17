<?php
/**
 * Template part for top panel in header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */
$top_switcher             = get_theme_mod( 'top_switcher', solosshopy_theme()->customizer->get_default( 'top_switcher' ) );

// Don't show top panel if all elements are disabled.
if ( ! solosshopy_is_top_panel_visible() ) {
	return;
}
?>

<div <?php echo solosshopy_get_html_attr_class( array( 'top-panel' ), 'top_panel_bg' ); ?>>
	<div class="container">
		<div class="top-panel__container">
			<?php solosshopy_top_message( '<div class="top-panel__message">%s</div>' ); ?>
			<?php solosshopy_contact_block( 'header_top_panel' ); ?>

			<div class="top-panel__wrap-items">
				<div class="top-panel__menus">
                    <?php solosshopy_login_link(); ?>
                    <?php solosshopy_top_menu(); ?>
					<?php solosshopy_social_list( 'header' ); ?>
				</div>
			</div>
            <?php if ( $top_switcher ) : ?>
            <div class="top-panel__part">
                <?php solosshopy_currency_switcher(); ?>
            </div>
            <?php endif; ?>
		</div>
	</div>
</div><!-- .top-panel -->
