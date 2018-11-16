<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 3.0.0
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'solosshopy' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'solosshopy' ); ?></span></span>

	<?php endif; ?>

	<?php $in_stock = ( is_null( $product->get_stock_quantity() ) ) ? esc_html__( 'not', 'solosshopy' ) : $product->get_stock_quantity(); ?>

	<span class="meta_stock"><?php esc_html_e( 'Availability:', 'solosshopy' ); ?> <span><?php echo sprintf( '%s %s', $in_stock, esc_html__( 'in Stock', 'solosshopy' ) ); ?></span></span>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>