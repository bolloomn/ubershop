<?php

	if ( is_page() ) {
		$post_id = $post->ID;
	}

	if( function_exists('is_shop') && is_shop()){
		$post_id = wc_get_page_id( 'shop' );
	}
	
	if(!empty($post_id)){
		$overwrite_sidebars = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX . 'overwrite_sidebars', true);
		$sidebar_right = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX . 'page_right_sidebar_id', true);
	}
	else{
		$overwrite_sidebars = '';
		$sidebar_right = '';
	}

	$right_sidebar_enabled = pukka_get_option('sidebar_right_enable') == 'on' ? true : false;
	
	$right_sidebar_enabled = apply_filters('pukka_right_sidebar', $right_sidebar_enabled);

	if('on' == $overwrite_sidebars){
		if(!empty($sidebar_right)){
		?>
		<div id="sidebar-right" class="sidebar basic">
			<?php dynamic_sidebar($sidebar_right); 	?>
		</div>
		<?php
		}
	}
	else if( $right_sidebar_enabled ) { ?>
		<div id="sidebar-right" class="sidebar basic">
		<?php
			if ( function_exists( 'pukka_is_really_wc_page' ) && pukka_is_really_wc_page() ) {
				if ( is_active_sidebar( 'sidebar-shop-right-1' ) ) {
					dynamic_sidebar( 'sidebar-shop-right-1' );
				}
			} else {

				if ( is_active_sidebar( 'sidebar-right-1' ) ) {
					dynamic_sidebar( 'sidebar-right-1' );
				}

				if ( is_active_sidebar( 'sidebar-right-2' ) ) {
					dynamic_sidebar( 'sidebar-right-2' );
				}
			}
			?>
			</div><!-- #sidebar-right -->
<?php }
