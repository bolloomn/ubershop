<?php

/**
 * Cart Link
 * Displayed a link to the cart including the number of items present and the cart total
 * @param  array $settings Settings
 * @return array           Settings
 * @since  1.0.0
 */
function solosshopy_cart_link() {

	?>
		<div class="cart-contents" title="<?php esc_html_e( 'View your shopping cart', 'solosshopy' ); ?>">
			<i class="nc-icon-mini shopping_bag-20"></i>
			<span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
		</div>
	<?php
}

function solosshopy_cart_link_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	solosshopy_cart_link();
	$fragments['div.cart-contents'] = ob_get_clean();
	return $fragments;
}

/**
 * Display Header Cart
 * @since  1.0.0
 * @uses  solosshopy_is_woocommerce_activated() check if WooCommerce is activated
 * @return void
 */
function solosshopy_header_cart() {
	get_template_part( 'woocommerce/header-cart' );
}
