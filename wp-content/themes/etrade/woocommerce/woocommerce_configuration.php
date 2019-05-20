<?php


// Disable the default WooCommerce stylesheet.
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

// product list widget
function pukka_register_woo_widgets(){
	include_once( 'widgets/widget-product_list.php' );
	register_widget( 'Pukka_Product_List' );
}
add_action( 'widgets_init', 'pukka_register_woo_widgets', 99 );

// Number of product to list on shop page
add_filter( 'loop_shop_per_page', function( $cols ) {

	if ( '' !== pukka_get_option( 'products_per_page' ) ) {
		$cols = pukka_get_option( 'products_per_page' );
	}

	return $cols;
}, 20 );

// Unregister Woocommerce Hooks
add_action( 'init', 'pukka_remove_wc_breadcrumbs' );
function pukka_remove_wc_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

// change order
// if( is_product_category() || is_product_tag() ){
	remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices' );
	add_action( 'woocommerce_archive_description', 'wc_print_notices', 20 );
// }

add_filter( 'post_class', 'pukka_woo_product_class', 10, 3 );
function pukka_woo_product_class( $classes, $class, $post_id ){


	if( is_singular('product') && $post_id == get_queried_object_id() ){
		$classes[] = 'basic';
		$classes[] = 'woo-single-product';
	}
	elseif( 'product' == get_post_type($post_id) ){
		$classes[] = 'product-block';
	}

	return $classes;
}



add_action('woocommerce_before_shop_loop_item', 'pukka_woo_woocommerce_before_shop_loop_item_wrapper_open', 1);
function pukka_woo_woocommerce_before_shop_loop_item_wrapper_open(){
	?>
	<div class="product-content">
	<?php
}

add_action('woocommerce_after_shop_loop_item', 'pukka_woocommerce_after_shop_loop_item_wrapper_end', 99);
function pukka_woocommerce_after_shop_loop_item_wrapper_end(){
	?>
	</div> <!-- .product-content -->
	<?php
}



add_action( 'woocommerce_after_shop_loop_item_title', 'pukka_woocommerce_after_shop_loop_item_title', 1);
function pukka_woocommerce_after_shop_loop_item_title(){
	global $product;

	$price_class = '';

	$target_product_types = array(
		'variable'
	);

	if ( in_array ( $product->get_type(), $target_product_types ) ) {

		if( method_exists($product, 'get_variation_price') ){
			$min_price = $product->get_variation_price('min');
			$max_price = $product->get_variation_price('max');
		}
		else{
			$min_price = $product->min_variation_price;
			$max_price = $product->max_variation_price;
		}

		if( $min_price !== $max_price ){
			$price_class = ' price-range';
		}

	}
	?>
<div class="product-data headings<?php echo $price_class; ?>">
<?php }
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 49 );

add_action( 'woocommerce_after_shop_loop_item_title', 'pukka_woocommerce_after_shop_loop_item_close', 999);
function pukka_woocommerce_after_shop_loop_item_close() { ?>
</div>
<?php } ?>
<?php add_action( 'woocommerce_after_shop_loop_item_title', 'pukka_woocommerce_after_shop_loop_item_content', 50);
function pukka_woocommerce_after_shop_loop_item_content(){ ?>
    <div class="more-detail">
        <div class="additional-links">
            <a class="read-more" href="<?php the_permalink(); ?>"><i class="fa fa-search"></i></a><a class="product-share" href="#"><i class="fa fa-share-alt"></i></a>
        </div>
        <?php if( function_exists('pukka_social_share') ){ pukka_social_share(); } ?>
    </div>
<?php } ?>
<?php
add_filter('woocommerce_loop_add_to_cart_link', 'pukka_woocommerce_loop_add_to_cart_link', 10);
function pukka_woocommerce_loop_add_to_cart_link( $html ){

	return str_replace('class="', 'class="buttons hide-detail ', $html);
}


/**
*   Ajax cart update
*/

add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	$fragments['.pukka-cart > a'] = '<a href="' . get_permalink(wc_get_page_id( 'cart' )) . '">' . sprintf(_n('<span class="txt-dk">%d<span> '. __('item', 'pukka'), '<span class="txt-dk">%d</span> '. __('items', 'pukka'), $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count) . '</a>';

	$fragments['.pukka-cart-value > a'] = '<a href="' . get_permalink(wc_get_page_id( 'cart' )) . '">' . $woocommerce->cart->get_cart_total() . '</a>';

	$fragments['.pukka-cart-content > .basket'] = '<div class="basket basic">' . get_cart_product_list() . '</div>';

	return $fragments;
}

/**
*   creates list of products in cart
*/

function get_cart_product_list(){
	global $woocommerce;

	$out = '<ul class="cart-product-list headings clearfix">';

	foreach($woocommerce->cart->get_cart() as $cart_item_key => $cart_item){
//		$_product = wc_get_product($cart_item['product_id']);
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		$out .= '<li class="cart-item clearfix">';
		$out .= '<div class="cart-item-remove">' . apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove fa fa-times" title="%s"></a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key ) . '</div>';

		$img = wp_get_attachment_image_src(get_post_thumbnail_id($cart_item['product_id']), 'shop_thumbnail');
		if(!empty($img[0])){
			$out .= '<img src="' . $img[0] . '" alt="' . esc_attr( $_product->get_title() ) . '" />';
		}
		$out .= '<h3>' . esc_html( $_product->get_title() ) . '</h3>';
		$out .= wc_get_formatted_cart_item_data( $cart_item );
		$out .= '<div class="cart-detail"><span class="quantity">' . $cart_item['quantity'] . '</span> &times; <span class="price">' . apply_filters( 'woocommerce_cart_item_price', $woocommerce->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) . '</span></div>';
		$out .= '</li>';
	}

	$out .= '</ul>';

	$out .= '<div class="cart-subtotal">' . __('Cart total: ', 'pukka') . $woocommerce->cart->get_cart_total() . '</div>';
	$out .= '<div class="cart-buttons">';
	$out .= '<a class="button view-cart wc-forward" href="' . wc_get_cart_url() . '">' . __('View cart', 'woocommerce') . '</a>';
	$out .= '<a class="button checkout wc-forward" href="' . wc_get_checkout_url() . '">' . __('Checkout', 'woocommerce') . '</a>';
	$out .= '<script type="text/javascript">jQuery(document).trigger("basket-refresh");</script>';
	$out .= '</div>';

	return $out;
}

/**
 * Override default WooCommerce function
 */
function woocommerce_output_related_products() {
	woocommerce_related_products(array('posts_per_page' => 4));
}





/* BEGIN Checkout: Add placeholders */
add_filter( 'woocommerce_form_field_args', 'pukka_form_field_args', 10, 3 );
function pukka_form_field_args( $args, $key, $value ) {

	if ( empty( $args['placeholder'] ) && ! empty( $args['label'] ) ) {
		$args['placeholder'] = $args['label'];
	}

	return $args;
}

/* END: Checkout: Add placeholders */





/**
 * pukka_is_really_wc_page
 * Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
 *
 * @access public
 * @return bool
 */
function pukka_is_really_wc_page () {

	// WooCommerce not active
	if ( ! function_exists( 'is_woocommerce' ) ){
		return false;
	}

	if ( is_woocommerce() ){
		return true;
	}

	$woocommerce_keys = array (
		"woocommerce_shop_page_id",
		"woocommerce_terms_page_id",
		"woocommerce_cart_page_id",
		"woocommerce_checkout_page_id",
		"woocommerce_pay_page_id",
		"woocommerce_thanks_page_id",
		"woocommerce_myaccount_page_id",
		"woocommerce_edit_address_page_id",
		"woocommerce_view_order_page_id",
		"woocommerce_change_password_page_id",
		"woocommerce_logout_page_id",
		"woocommerce_lost_password_page_id",
	);

	foreach ( $woocommerce_keys as $wc_page_id ) {
		if ( get_the_ID () != '' && get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
			return true ;
		}
	}
	return false;
}



add_action( 'woocommerce_cart_collaterals', 'pukka_woocommerce_cart_collaterals', 10 );
/**
 * Adds coupon code form.
 *
 * @param array $args The formarguments.
 */
function pukka_woocommerce_cart_collaterals( $args ) {
	global $woocommerce;
	?>
	<div class="shipping-coupon">
		<?php if ( wc_coupons_enabled() ) : ?>
			<div class="coupon">
				<label for="pukka-coupon-code"><?php esc_attr_e( 'Coupon', 'pukka' ); ?></label>
				<input type="text" name="coupon_code" class="input-text" id="pukka-coupon-code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
				<input type="submit" class="pukka-apply-coupon button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'pukka' ); ?>" />
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php

	wc_get_template( 'cart/shipping-calculator.php' );
}

/**
 * Override Woo function.
 */
function woocommerce_shipping_calculator() {
	if ( ! is_cart() ) {
		wc_get_template( 'cart/shipping-calculator.php' );
	}
}


/*****************************************************/
/*          WOOCOMMERCE 2.3 UPDATE CHANGES           */
/*****************************************************/

/*We removed new 2.3 WC 'Proceed to Checkout' button on Cart Page with this action*/
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 10 );

