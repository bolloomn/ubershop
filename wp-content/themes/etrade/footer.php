

	</div><!-- #main -->

	<?php get_sidebar('right'); ?>
	</div><!-- #wrapper -->
</div><!-- .width-wrap -->
<?php
	/*
	* footer and bottom stripe (footer menu) settings
	* all of this can be disabled from backend, we apply filters for easier customization
	*
	*/

	$enable_primary_footer = is_active_sidebar('sidebar-footer-1');
	$enable_secondary_footer = is_active_sidebar('sidebar-footer-2');
	$enable_footer_menu = pukka_get_option('enable_footer_menu') == 'on' ? true : false;

	// apply filters
	$enable_primary_footer = apply_filters('pukka_primary_footer', $enable_primary_footer);
	$enable_secondary_footer = apply_filters('pukka_secondary_footer', $enable_secondary_footer);
	$enable_footer_menu = apply_filters('pukka_footer_menu', $enable_footer_menu);

?>

	<?php if( $enable_secondary_footer) : ?>
	<div id="secondary-footer" class="basic">
		<div class="widget-area boxed">
			<?php dynamic_sidebar( 'sidebar-footer-2' ); ?>
		</div><!-- .widget-area -->
	</div> <!-- #secondary-footer -->
	<?php endif; ?>

	<?php if( $enable_primary_footer ) : ?>
	<footer id="footer">
		<div class="widget-area boxed">
			<?php dynamic_sidebar( 'sidebar-footer-1' ); ?>
		</div><!-- .widget-area -->
	</footer> <!-- #footer -->
	<?php endif; ?>

	<?php if( $enable_footer_menu ) : ?>
	<div id="bottom-stripe" class="headings clearfix">
		<div class="boxed">
			<?php if( pukka_get_option('footer_menu_text') != '' ) : ?>
					<div class="secondary-menu-text footer-menu-text"><?php echo pukka_get_option('footer_menu_text'); ?></div>
			<?php endif; ?>
			<?php
				// bottom-menu
			?>
			<div id="footer-menu">
				<?php

					wp_nav_menu(
						array(
						'theme_location' => 'footer',
						'menu_class' => 'menu stripe-menu',
						'container' => false,
						// 'items_wrap' => $items_wrap,
						'fallback_cb' => 'pukka_page_menu'
						)
					);

					$soc_menu_pos = (array)pukka_get_option('social_icons_position');
					if( in_array('footer-menu', $soc_menu_pos) ){
						echo '<div class="social-menu-wrap">' . pukka_social_menu(false) . '</div>';
					}
				?>
			</div> <!-- #bottom-menu -->
		</div><!-- .width-wrap -->
	</div> <!-- #bottom-stripe -->
	<?php endif; // if( pukka_get_option('enable_footer_menu') == 'on' ) ?>


<a href="#top" id="top-link"><i class="fa fa-chevron-up"></i></a>

<?php wp_footer(); ?>
</body>
</html>
