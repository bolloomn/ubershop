<?php
	if ( is_page() ) {
		$post_id = $post->ID;
	}

	if( function_exists('is_shop') && is_shop() ){
		$post_id = wc_get_page_id( 'shop' );
	}
	
	if(!empty($post_id)){
		$overwrite_sidebars = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX . 'overwrite_sidebars', true);
		$sidebar_left = get_post_meta($post_id, PUKKA_POSTMETA_PREFIX . 'page_left_sidebar_id', true);
	}else{
		$overwrite_sidebars = '';
		$sidebar_left = '';
	}

	// left sidebar
	$left_sidebar_enabled = ('left' == pukka_get_option('main_menu_position') || 'on' == pukka_get_option('sidebar_left_enable')); //(('left' == pukka_get_option('main_menu_position') || 'on' == pukka_get_option('sidebar_left_enable')) && 'on' != $overwrite_sidebars);
	$left_sidebar_enabled = apply_filters('pukka_left_sidebar', $left_sidebar_enabled);


    if (is_front_page() ) {
	?>
    <div id="sidebar-left" class="sidebar basic headings">
        <div id="widget-area-left">
            <?php dynamic_sidebar( 'sidebar-shop-left-1' ); ?>
        </div>
    </div>
    <?php  } ?>

<?php


	if('on' == $overwrite_sidebars){ ?>
		<?php if(!empty($sidebar_left) || 'left' == pukka_get_option('main_menu_position')) { ?>
			<div id="sidebar-left" class="sidebar basic headings">
			<?php
			if('left' == pukka_get_option('main_menu_position')) { ?>
				<div id="sidebar-logo"><a href="<?php echo home_url(); ?>"><?php pukka_logo(); ?></a></div>
				<div id="main-menu" class="clearfix">
					<?php 
						wp_nav_menu(array(
									'theme_location' => 'primary',
									'class' => '',
									'container_class' => 'menu clearfix',
									'walker' => new PukkaNavDropdown,
									)
								);
					?>
				</div><!-- #main-menu -->
			<?php } ?>
				<div id="widget-area-left"><?php 
				if(!empty($sidebar_left)){
					dynamic_sidebar($sidebar_left); 
				}
				?></div>
			</div>
			<?php
		}
	}else if( $left_sidebar_enabled /*'left' == pukka_get_option('main_menu_position') || 'on' == pukka_get_option('sidebar_left_enable')*/) { ?>
			<div id="sidebar-left" class="sidebar basic headings">
				<!-- SIDEBAR MENU -->
			<?php if('left' == pukka_get_option('main_menu_position')) { ?>
				<div id="sidebar-logo"><a href="<?php echo home_url(); ?>"><?php pukka_logo(); ?></a></div>
				<div id="main-menu" class="clearfix">
					<?php 
						wp_nav_menu(array(
									'theme_location' => 'primary',
									'class' => '',
									'container_class' => 'menu clearfix',
									'walker' => new PukkaNavDropdown,
									)
								);
					?>
				</div><!-- #main-menu -->
			<?php } ?>
			<!-- END SIDEBAR MENU -->
				<div id="widget-area-left">
					<?php
						if ( function_exists( 'pukka_is_really_wc_page' ) && pukka_is_really_wc_page() ) {
							if ( is_active_sidebar( 'sidebar-shop-left-1' ) ) {
								dynamic_sidebar( 'sidebar-shop-left-1' );
							}
						} else {
							if ( is_active_sidebar( 'sidebar-left-1' ) ) {
								dynamic_sidebar( 'sidebar-left-1' );
							}

							if ( is_active_sidebar( 'sidebar-left-2' ) ) {
								dynamic_sidebar( 'sidebar-left-2' );
							}
						}
					?>
				</div>
			</div>
			
<?php 		}