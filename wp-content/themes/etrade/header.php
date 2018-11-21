<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo home_url('wp-content/plugins/bollooMn/css/font-awesome/css/font-awesome.min.css'); ?>" >
</head>

<body <?php body_class(); ?>>

<?php pukka_after_body(); ?>
<div id="responsive-check">
	<div id="menu-toggle" class="fa fa-bars"></div>
	<a id="responsive-title" href="<?php echo home_url(); ?>">
		<?php
			if( pukka_get_option('responsive_logo_img_id') != '' ){
				$responsive_logo = wp_get_attachment_image_src( pukka_get_option('responsive_logo_img_id'), 'full');
				echo '<img src="'. $responsive_logo[0] .'" style="vertical-align:baseline;" />';
			}
			else{
				bloginfo('name');
			}
		?>
	</a>
	<?php if( function_exists('is_woocommerce') ) : ?>
	<a href="<?php echo get_permalink(wc_get_page_id( 'cart' )); ?>" id="responsive-cart" class="fa fa-shopping-cart"></a>
	<?php endif; ?>
</div>
<?php
if('top' == pukka_get_option('main_menu_position')) :

	// build css classes
	$header_classes = ( pukka_get_option('menu_type') != '' )
					? ' menu-'. pukka_get_option('menu_type')
					: '';
?>
<div id="menu-top" class="basic headings full <?php echo esc_attr($header_classes); ?>">
		
		<?php
			/*
			* secondary menu can be disabled from backend
			* we apply filters for easier customization
			*/

			$enable_secondary_menu = pukka_get_option('enable_secondary_menu') == 'on' ? true : false;
			$enable_secondary_menu = apply_filters('pukka_secondary_menu', $enable_secondary_menu);
		?>

		<?php if( $enable_secondary_menu ) : ?>
		<div id="menu-secondary" class="clearfix">
			<div class="boxed">
				<?php if( pukka_get_option('secondary_menu_text') != '' ) : ?>
				<div class="secondary-menu-text"><?php echo pukka_get_option('secondary_menu_text'); ?></div>
				<?php endif; ?>
				<div class="secondary-container clearfix">
                    <ul class="menu stripe-menu">
                        <li class="menu-item">
                            <a href="#">Нэвтрэх</a>
                        </li>
                        <li class="menu-item">
                            <a href="#">Бүртгүүлэх</a>
                        </li>
                    </ul>

				</div>
			</div>
		</div><!-- #menu-secondary -->
		<?php endif; // if( $enable_secondary_menu ) ?>

		<div id="menu-primary" class="boxed clearfix">
			<div id="search-outer">
				<div id="search">
					<form action="<?php echo home_url(); ?>" method="get" role="search">
							<input type="text" placeholder="<?php _e('Search...', 'pukka'); ?>" autocomplete="off" id="s-main" name="s" value="">
							<div id="searchsubmit-main" class="button"><span class="fa fa-search"></span></div>
					</form>
				</div> <!-- #search -->
			</div>
			<div class="width-wrap">
				<a href="<?php echo home_url(); ?>"><div id="top-logo"><?php pukka_logo(); ?></div></a>
				<div id="main-menu">
					<div class="menu">
					<?php 
						$menu_args = array(
										'theme_location' => 'primary',
										'menu_class' => '',
										'container' => false,
										'fallback_cb' => 'pukka_page_menu',
									);

						// is there a menu assigned to this position
						if( has_nav_menu('primary') ){
//							$menu_args['walker'] = new PukkaNavDropdown;
							$menu_args['walker'] = new PukkaNavWide;
						}

						wp_nav_menu($menu_args);
					?>
					</div>
				</div><!-- #main-menu -->
				<?php pukka_shop_menu(); ?>
			</div>
		</div><!-- #menu-primary -->
	</div>
<?php endif; ?>
<div class="width-wrap">

	<?php if('left' ==  pukka_get_option('main_menu_position')) : ?>
		<div id="shop-bar" class="width-wrap">
			<?php pukka_shop_menu(); ?>
			<div id="shop-toggle" class="fa fa-shopping-cart"></div>
		</div>
	<?php endif; ?>

	<div id="wrapper" class="clearfix">
	<?php get_sidebar(); ?>
		<div id="main">