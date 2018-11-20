<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

if ( $product->is_on_sale() ) {
	echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale buttons">' . __( 'Sale!', 'pukka' ) . '</span>', $post, $product );
}

if ( ! $product->is_in_stock() ) {
	echo apply_filters( 'woocommerce_sale_flash', '<span class="band-wrap"><span class="onsale no-stock buttons">' . __( 'Out of Stock!', 'pukka' ) . '</span></span>', $post, $product );
} 
