<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  Automattic
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );



$price= get_post_meta($post->ID, '_price', true);
$discounts=get_post_meta($post->ID, 'phoen_woocommerce_discount_mode', true);

if(is_array($discounts)){
    $table='<table class="Ptable">
            <thead><tr><th>Хэмжээ</th><th>Үнэ</th></tr></thead>
            <tbody>';
            foreach ($discounts as $discount){
                $table .='<tr>';
                if($discount["max_val"]==''){
                    $table .='<td>'.$discount["min_val"].'-c их</td>';
                } else {
                    $table .='<td>'.$discount["min_val"].'-c '.$discount["max_val"].'</td>';
                }

                if($discount["type"]=='amount'){
                    $t_price=number_format($price-$discount["discount"], 2, '.', '');
                    $table .='<td>'.$t_price.' ₮</td>';
                } else {
                    $t_price=number_format($price*(100-$discount["discount"])/100, 2, '.', '');
                    $table .='<td>'.$t_price.' ₮</td>';
                }
                $table .='</tr>';
            }
    $table .='</tbody></table>';
}

?>
<?php
if ( ! $short_description ) {
    return;
}
?>
<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; ?>


    <?php echo $table; ?>

</div>
