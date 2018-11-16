<?php
/**
 * Solosshopy WooCommerce Theme hooks.
 *
 * @package Solosshopy
 */

/**
 * Enable Woocommerce theme support
 */

function solosshopy_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

/**
 * Change WooCommerce loop category title layout
 *
 * @param object $category
 *
 * @return string
 */
function solosshopy_woocommerce_template_loop_category_title( $category ) {
	?>
	<div class="title_count_block">
	  <?php if ( 0 < $category->count ) {
		  echo '<a href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '"  class="count">';
		  echo apply_filters( 'woocommerce_subcategory_count_html', sprintf( ' <span><span class="count__number">%s</span> %s</span>', $category->count, esc_html__( 'products', 'solosshopy' ) ), $category );
		  echo '</a>';
	  }
	  ?>
		<h3><?php
		echo '<a href="' . get_term_link( $category, 'product_cat' ) . '">';
		echo wp_kses_post( $category->name );
		echo '</a>';
		?>
		</h3>
	</div>
	<?php
}


function solosshopy_tm_categories_carousel_widget_arrows_pos() {
	return 'outside';
}

function solosshopy_products_format_sale_end_date() {
	return sprintf( '<span>%%D <i>%1$s</i></span> <span>%%H <i>%2$s</i></span><span>%%M <i>%3$s</i></span>', esc_html( 'days', 'solosshopy' ), esc_html( 'Hrs', 'solosshopy' ), esc_html__( 'Min', 'solosshopy' ) );
}

function solosshopy_products_sale_end_date() {
	global $product;
	$sale_end_date = get_post_meta( get_the_id(), '_sale_price_dates_to', true );
	if ( ! $sale_end_date ) {
		return;
	}

	$data_format = apply_filters( 'tm_products_sale_end_date_format', '%D %H:%M:%S' );

	$countdown    = array(
		'date'   => date( 'Y/m/d', $sale_end_date ),
		'format' => $data_format,
	);
	$sale_end_str = '<span class="tm-products-sale-end-date" data-countdown=\'%s\'></span>';

	printf( wp_kses( $sale_end_str, array(
		'span' => array(
			'class'          => array(),
			'data-countdown' => array(),
		),
	) ), json_encode( $countdown ) );
}

/**
 * Add WooCommerce 'New' and 'Featured' Flashes
 *
 */
function solosshopy_woocommerce_show_flash() {
	global $product;

	if ( ! $product->is_on_sale() ) {

		if ( 604800 > ( date( 'U' ) - strtotime( get_the_date() ) ) ) {
			echo '<span class="new">' . esc_html__( 'New', 'solosshopy' ) . '</span>';
		} else if ( $product->is_featured() ) {
			echo '<span class="featured">' . esc_html__( 'Featured', 'solosshopy' ) . '</span>';
		}
	}
}

/**
 * Change WooCommerce Price Output when Product is on Sale
 *
 * @param  string $price Price
 * @param  int|string $from Regular price
 * @param  int|string $to Sale price
 *
 * @return string
 */
function solosshopy_woocommerce_get_price_html_from_to( $price, $from, $to ) {
	$price = '<ins>' . ( ( is_numeric( $to ) ) ? wc_price( $to ) : $to ) . '</ins> <del>' . ( ( is_numeric( $from ) ) ? wc_price( $from ) : $from ) . '</del>';

	return $price;
}


/**
 * Open wrap comapre & wishlist button grid-listing
 *
 */
function solosshopy_compare_wishlist_wrap_open( $atts = '' ) {
	if ( 'list' !== $atts ) {
		echo '<div class="wishlist_compare_button_block">';
	}
}

/**
 * Close wrap comapre & wishlist button grid-listing
 *
 */
function solosshopy_compare_wishlist_wrap_close( $atts = '' ) {
	if ( 'list' !== $atts ) {
		echo '</div>';
	}
}

/**
 * Open wrap comapre & wishlist button list-listing
 *
 */
function solosshopy_compare_wishlist_wrap_list_open( $atts = '' ) {
	if ( 'list' === $atts ) {
		echo '<div class="wishlist_compare_button_block">';
	}
}


/**
 * Close wrap comapre & wishlist button list-listing
 *
 */
function solosshopy_compare_wishlist_wrap_list_close( $atts = '' ) {
	if ( 'list' === $atts ) {
		echo '</div>';
	}
}

/**
 * Open wrap bottom content
 *
 */
function solosshopy_bottom_wrap_open( $atts = '' ) {
	if ( 'list' !== $atts ) {
		echo '<div class="content_bottom_wrap_block">';
	}
}

/**
 * Close wrap bottom content
 *
 */
function solosshopy_bottom_wrap_close( $atts = '' ) {
	if ( 'list' !== $atts ) {
		echo '</div>';
	}
}

/**
 * Rewrite functions compare & wishlist for grid or listing layouts
 *
 */
function solosshopy_woocompare_add_button_loop( $atts = '' ) {
	if ( 'list' !== $atts && function_exists( 'tm_woocompare_add_button_loop' ) ) {
		tm_woocompare_add_button_loop( $atts );
	}
}

function solosshopy_woocompare_add_button_loop_list( $atts = '' ) {
	if ( 'list' === $atts && function_exists( 'tm_woocompare_add_button_loop' ) ) {
		tm_woocompare_add_button_loop( $atts );
	}
}

function solosshopy_woowishlist_add_button_loop( $atts = '' ) {
	if ( 'list' !== $atts && function_exists( 'tm_woowishlist_add_button_loop' ) ) {
		tm_woowishlist_add_button_loop( $atts );
	}
}

function solosshopy_woowishlist_add_button_loop_list( $atts = '' ) {
	if ( 'list' === $atts && function_exists( 'tm_woowishlist_add_button_loop' ) ) {
		tm_woowishlist_add_button_loop( $atts );
	}
}

/**
 * Rewrite functions rating for grid or listing layouts
 *
 */

function solosshopy_woocommerce_template_loop_rating( $atts = '' ) {
	if ( 'list' !== $atts ) {
		woocommerce_template_loop_rating( $atts );
	}
}

function solosshopy_woocommerce_template_loop_rating_list( $atts = '' ) {
	if ( 'list' === $atts ) {
		woocommerce_template_loop_rating( $atts );
	}
}

/**
 * Change quickview button position
 *
 * @return number
 */

function solosshopy_tm_woo_quick_view_button_hook( $data ) {
	$atts = '';

	if ( class_exists( 'TM_WC_Ajax_Filters' ) && function_exists( 'tm_wc_grid_list' ) && isset( tm_wc_grid_list()->condition ) ) {
		$atts = tm_wc_grid_list()->condition;
	}

	if ( 'list' === $atts && ( is_archive() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) ) {
		$data['hook']     = 'woocommerce_after_shop_loop_item';
		$data['priority'] = 10;
	} else {
		$data['hook']     = 'woocommerce_before_shop_loop_item';
		$data['priority'] = 4;
	}

	return $data;
}

/**
 * Added custom thumbnail size for listing-line category thumbnail
 *
 */

function solosshopy_woocommerce_subcategory_thumbnail( $category, $atts = '' ) {
	global $_wp_additional_image_sizes;
	if ( 'list' === $atts ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_list_size', 'solosshopy-thumb-listing-line-product' );
		$image_sizes          = get_intermediate_image_sizes();

		if ( in_array( $small_thumbnail_size, $image_sizes ) ) {
			$dimensions['width']  = $_wp_additional_image_sizes[ $small_thumbnail_size ]['width'];
			$dimensions['height'] = $_wp_additional_image_sizes[ $small_thumbnail_size ]['height'];
			$dimensions['crop']   = $_wp_additional_image_sizes[ $small_thumbnail_size ]['crop'];
		} else {
			$dimensions['width']  = '300';
			$dimensions['height'] = '300';
			$dimensions['crop']   = 1;
		}

	} else {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'solosshopy-thumb-grid-line-product' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
	}

	$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );

	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
		$image = $image[0];
	} else {
		$image = wc_placeholder_img_src();
	}

	if ( $image ) {
		// Prevent esc_url from breaking spaces in urls for image embeds
		// Ref: https://core.trac.wordpress.org/ticket/23605
		$image = str_replace( ' ', '%20', $image );

		echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
	}
}


/**
 * Add links into product title
 *
 */

function solosshopy_template_loop_product_title() {
	echo '<h3><a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">' . get_the_title() . '</a></h3>';
}

/**
 * Added custom thumbnail size for listing-line products
 *
 */

function solosshopy_get_product_thumbnail_size( $atts = '' ) {
	if ( 'list' === $atts ) {
		echo woocommerce_get_product_thumbnail( 'solosshopy-thumb-listing-line-product' );
	} else {
		echo woocommerce_get_product_thumbnail();
	}
}


/**
 * Wrap content single product
 *
 */
function solosshopy_single_product_open_wrapper() {
	echo '<div class="single_product_wrapper">';
}

function solosshopy_single_product_close_wrapper() {
	echo '</div>';
}

/**
 * Open wrap product loop elements
 *
 */
function solosshopy_product_image_wrap_open() {
	echo '<div class="block_product_thumbnail">';
}

/**
 * Close wrap product loop elements
 *
 */
function solosshopy_product_image_wrap_close() {
	echo '</div>';
}

/**
 * Open wrap content product loop elements
 *
 */
function solosshopy_product_content_wrap_open() {
	echo '<div class="block_product_content">';
}

/**
 * Open wrap content product loop elements
 *
 */
function solosshopy_product_content_wrap_close() {
	echo '</div>';
}


/**
 * Assets woocommerce script
 *
 */

function solosshopy_enqueue_assets() {

	if ( solosshopy_is_woocommerce_activated() ) {

		// Scripts
		wp_register_script( 'jquery-easyzoom', SOLOSSHOPY_THEME_URI . '/assets/js/min/easyzoom.min.js', array( 'jquery' ), '2.3.1', true );
		wp_enqueue_script( 'solosshopy-single-product-init', SOLOSSHOPY_THEME_URI . '/assets/js/single-product.js', array( 'jquery-easyzoom' ), '1.0.0', true );
		wp_enqueue_script( 'countdown-script', SOLOSSHOPY_THEME_URI . '/assets/js/min/jquery.countdown.min.js', array(), '2.1.0', true );
		wp_enqueue_script( 'solosshopy-woo-theme-script', SOLOSSHOPY_THEME_URI . '/assets/js/woo-theme-script.js', array(), '1.0.0', true );

		// Styles
		wp_enqueue_style( 'solosshopy-woocommerce-styles', get_template_directory_uri() . '/assets/css/style-woocommerce.css', array(), SOLOSSHOPY_THEME_VERSION );
	}
}


function solosshopy_thumb_cols() {
	return 5; // .last class applied to every 4th thumbnail
}

/*
 *
 * Wrap products carousel - div
 */

function solosshopy_tm_products_carousel_widget_wrapper_open() {
	return '<div class="swiper-wrapper tm-products-carousel-widget-wrapper products">';
}

function solosshopy_tm_products_carousel_widget_wrapper_close() {
	return '</div>';
}

add_filter( 'tm_products_carousel_widget_arrows_separator', 'solosshopy_products_carousel_widget_arrows_separator' );
add_filter( 'tm_products_categories_widget_arrows_separator', 'solosshopy_products_carousel_widget_arrows_separator' );


function solosshopy_products_carousel_widget_arrows_separator() {
	return '<div class="swiper-counter"></div>';
}

/**
 * Output the related products.
 *
 * @subpackage  Product
 */
function solosshopy_woocommerce_output_related_products( $args = array() ) {

	$args = array(
		'posts_per_page' => 10,
		'columns'        => 4,
		'orderby'        => 'rand'
	);

	return $args;
}


/*
 *
 * Page Cart Checkout
 */

function solosshopy_woocommerce_cross_sell_display( $posts_per_page = 2, $columns = 4, $orderby = 'rand' ) {

	if ( is_checkout() ) {
		return;
	}

	wc_get_template( 'cart/cross-sells.php', array(
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby,
		'columns'        => $columns,
	) );

}


/**
 * Open wrapper for carousel loop products
 *
 * @param array Provided arguments
 * @param bool Columns argument for backwards compat
 * @param bool Order by argument for backwards compat
 */
function solosshopy_woocommerce_product_loop_carousel_start( $echo = true, $swiper = false ) {

	$uniqid            = uniqid();
	$GLOBALS['uniqid'] = $uniqid;
	$sidebar_position  = get_theme_mod( 'sidebar_position' );
	$slides_per_view   = 'fullwidth' === $sidebar_position || is_product() ? '4' : '3';
	$data_attr_line    = 'class="solosshopy-carousel swiper-container"';
	$data_attr_line .= ' data-uniq-id="swiper-carousel-' . $uniqid . '"';
	$data_attr_line .= ' data-slides-per-view="' . $slides_per_view . '"';
	$data_attr_line .= ' data-slides-per-group="1"';
	$data_attr_line .= ' data-slides-per-column="1"';
	$data_attr_line .= ' data-space-between-slides="50"';
	$data_attr_line .= ' data-duration-speed="500"';
	$data_attr_line .= ' data-swiper-loop="false"';
	$data_attr_line .= ' data-free-mode="false"';
	$data_attr_line .= ' data-grab-cursor="true"';
	$data_attr_line .= ' data-mouse-wheel="false"';

	$html = '<div class="swiper-carousel-container">';
	$html .= '<div id="swiper-carousel-' . $uniqid . '" ' . $data_attr_line . '>';
	$html .= '<div class="swiper-wrapper">';
	if ( true === $echo ) {
		echo 	wp_kses( $html, array(
		'div' => array(
			'id'          => array(),
			'class'          => array(),
			'data-uniq-id' => array(),
			'data-countdown' => array(),
			'data-slides-per-view' => array(),
			'data-slides-per-group' => array(),
			'data-slides-per-column' => array(),
			'data-space-between-slides' => array(),
			'data-duration-speed' => array(),
			'data-swiper-loop' => array(),
			'data-free-mode' => array(),
			'data-grab-cursor' => array(),
			'data-mouse-wheel' => array(),
		),
	) );
	} else {
		return $html;
	}
}


/**
 * Closed wrapper for carousel loop products
 *
 * @param type|bool $echo
 * @param type|bool $swiper
 */
function solosshopy_woocommerce_product_loop_carousel_end( $echo = true, $swiper = false ) {

	global $uniqid;
	$html = '</div>';
	$html .= '</div>';
	$html .= '<div id="swiper-carousel-' . $uniqid . '-next" class="swiper-button-next button-next"></div><div class="swiper-counter"></div><div id="swiper-carousel-' . $uniqid . '-prev" class="swiper-button-prev button-prev"></div>';
	$html .= '</div>';
	unset( $GLOBALS['uniqid'] );
	if ( true === $echo ) {
		echo wp_kses_post( $html );
	} else {
		return $html;
	}
}


/*
 *
 * Function for Smartbox thumbnail
 */
function solosshopy_woocommerce_template_loop_product_thumbnail_custom_size() {
	echo woocommerce_get_product_thumbnail( 'solosshopy-smart-box-thumb' );
}

function solosshopy_products_smart_box_widget__cat_img_size() {
	return 'solosshopy-thumb-listing-line-product';
}

/*
 *
 * Remove woo-pagination and added theme pagination
 */
function solosshopy_woocommerce_pagination_args( $args = array() ) {

	unset( $args['type'] );
	unset( $args['end_size'] );
	unset( $args['mid_size'] );

	return $args;
}


/**
 * Add product categories in product list
 *
 * @return string
 */
function solosshopy_woocommerce_list_categories() {
	$sep    = '</li> <li>';
	$before = '<ul class="product-categories"><li>';
	$after  = '</li></ul>';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '>=' ) ) {
		echo wc_get_product_category_list( get_the_id(), $sep, $before, $after );
	} else {
		global $product;
		if ( ! empty( $product ) ) {
			echo wp_kses_post( $product->get_categories( $sep, $before, $after ) );
		}
	}
}

/**
 * Display short description for listing-line template product
 *
 */
function solosshopy_woocommerce_display_short_excerpt( $atts = '' ) {
	if ( 'list' === $atts ) {
		echo '<div class="desc_products_listing_line">';
		woocommerce_template_single_excerpt( $atts );
		echo '</div>';
	}
}


/**
 * Add layout synchronization for product loop and product carousel widget.
 *
 */
function solosshopy_products_carousel_widget_hooks( $hooks ) {

	$hooks['cat'] = array(
		'woocommerce_before_shop_loop_item_title',
		'solosshopy_woocommerce_list_categories',
		14,
		1
	);

	$hooks['title'] = array(
		'woocommerce_shop_loop_item_title',
		'solosshopy_template_loop_product_title',
		10,
		1
	);

	$hooks['price'] = array(
		'woocommerce_after_shop_loop_item',
		'woocommerce_template_loop_price',
		5,
		1
	);

	return $hooks;
}

function solosshopy_wc_compare_wishlist_ahax_filters_loader() {
	return '<div class="page-preloader"></div>';
}

/**
 * Replace default breadcrubs trail with WooCommerce-related.
 *
 * @param  bool $is_custom_breadcrumbs Default cutom breadcrumbs trigger.
 * @param  array $args Breadcrumb arguments.
 *
 * @return bool|array
 */
function solosshopy_get_woo_breadcrumbs( $is_custom_breadcrumbs, $args ) {

	if ( ! function_exists( 'is_woocommerce' ) || ! is_woocommerce() ) {
		return false;
	}

	if ( ! class_exists( 'Solosshopy_Woo_Breadcrumbs' ) ) {
		require_once trailingslashit( SOLOSSHOPY_THEME_CLASSES ) . '/class-woocommerce-breadcrumbs.php';
	}

	$woo_breadcrums = new Solosshopy_Woo_Breadcrumbs( solosshopy_theme()->get_core(), $args );

	return array( 'items' => $woo_breadcrums->items, 'page_title' => $woo_breadcrums->page_title );

}

/**
 * Check availability tm_wc_ajax - plugin
 *
 * @return bool
 */
function solosshopy_is_ajax_shop() {
	if ( function_exists( 'tm_wc_ajax_is_shop' ) ) {
		return tm_wc_ajax_is_shop();
	} else {
		return false;
	}
}

/**
 * Add wrapp to content product
 *
 * @return bool
 */

function solosshopy_add_content_top_wrap_block_open( $atts = '' ) {
	if ( 'list' !== $atts ) {
		echo '<div class="content_top_wrap_block">';
	}
}

function solosshopy_add_content_top_wrap_block_close( $atts = '' ) {
	if ( 'list' !== $atts ) {
		echo '</div>';
	}
}

/**
 * Add wrap to woocompare_button
 */
function solosshopy_woocompare_button( $html = '', $classes, $id, $nonce, $text, $preloader ) {

	$html = sprintf( '<div class="wrap_compare" ><button type="button" class="%s" data-id="%s" data-nonce="%s">%s</button></div>', implode( ' ', $classes ), $id, $nonce, $text . $preloader );

	return $html;

}

/**
 * Add wrap to woowishlist_button
 */
function solosshopy_woowishlist_button( $html = '', $classes, $id, $nonce, $text, $preloader ) {

	$html = sprintf( '<div class="wrap_wish" ><button type="button" class="%s" data-id="%s" data-nonce="%s">%s</button></div>', implode( ' ', $classes ), $id, $nonce, $text . $preloader );

	return $html;

}

function solosshopy_single_product_add_footer_meta() {
	wc_get_template( 'single-product/meta-footer.php' );
}

function solosshopy_ajax_product_filter_wrap_open() {
	echo '<div class="tm-wc-ajax-filters-panel">';
}


function solosshopy_ajax_product_filter_wrap_close() {
	echo '</div>';
}


function solosshopy_account_page_content_title() {
	$title          = '';
	$endpoint       = WC()->query->get_current_endpoint();
	$endpoint_title = WC()->query->get_endpoint_title( $endpoint );

	if ( is_account_page() ) {
		$title = sprintf( '<h3 class="account-page-content-title">%s</h3>', esc_html__( 'Dashboard', 'solosshopy' ) );
	}

	if ( $endpoint_title ) {
		$title = sprintf( '<h3 class="account-page-content-title">%s</h3>', $endpoint_title );
	}

	echo wp_kses_post( $title );

}
add_filter( 'cherry_customizer_core_sections', 'soloshopy_customizer_core_sections' );

function soloshopy_customizer_core_sections( $sections ) {
	$sections[] = 'woocommerce_product_catalog';

	return $sections;
}