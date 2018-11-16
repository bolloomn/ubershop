<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $total <= 1 ) {
	return;
}
?>
<nav class="woocommerce-pagination nav-links">
	<?php
	echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
		'base'      => $base,
		'format'    => $format,
		'add_args'  => false,
		'current'   => max( 1, $current ),
		'total'     => $total,
		'prev_text' => '<span> <i class="nc-icon-mini arrows-1_tail-triangle-left"></i>' . esc_html__( 'Prev', 'solosshopy' ) . '</span>',
		'next_text' => '<span> ' . esc_html__( 'Next', 'solosshopy' ) . '<i class="nc-icon-mini arrows-1_tail-triangle-right"></i></span>',
		'type'      => 'list',
		'end_size'  => 3,
		'mid_size'  => 3,
	) ) );
	?>
</nav>
