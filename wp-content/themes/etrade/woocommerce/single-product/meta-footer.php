<?php
/**
 * Single Product Meta Footer
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>
<div class="product_meta product_meta__footer">

	<?php
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '>=' ) ) {
			echo wc_get_product_category_list( $product->get_id(), ' ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'solosshopy' ) . ' ', '</span>' );
			echo wc_get_product_tag_list( $product->get_id(), ' ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'solosshopy' ) . ' ', '</span>' );
		} else {
			echo wp_kses_post( $product->get_categories( ' ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'solosshopy' ) . ' ', '</span>' ) );
			echo wp_kses_post( $product->get_tags( ' ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'solosshopy' ) . ' ', '</span>' ) );
		}
	?>

</div>
