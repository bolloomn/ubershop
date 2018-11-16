<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

		<?php /* BEGIN: Pukka modification */ ?>
		<?php
			// Get custom category header
			$queried_object = get_queried_object();

			if (isset($queried_object->term_id)){

				// put the term ID into a variable
				$term_id = $queried_object->term_id;

				// retrieve the existing value(s) for this meta field. This returns an array
				$term_meta = get_option( "taxonomy_$term_id" );

				if(isset($term_meta['cat_header'])) : ?>
					<div class="cat-header">
					<?php echo do_shortcode($term_meta['cat_header']); ?>
					</div> <!-- .cat-header -->
				<?php endif;
			}
		?>
		<?php /* END: Pukka modification */ ?>

		<?php
			/**
			 * woocommerce_before_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 */
			do_action( 'woocommerce_before_main_content' );
		?>

	<?php /* BEGIN: Pukka modification */ ?>
		<div class="shop-heading clearfix">

		<?php
			// Output the WooCommerce Breadcrumb
			woocommerce_breadcrumb();
		?>

		<?php if ( have_posts() ) : ?>
			<div class="catalog-options">
			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
			</div>
		<?php endif; ?>

		<?php
			/**
			 * woocommerce_archive_description hook
			 *
			 * @hooked wc_print_notices - 20
			 */
			do_action( 'woocommerce_archive_description' );
		?>

		</div> <!-- .shop-heading -->
		<?php /* END: Pukka modification */ ?>

<?php
if ( woocommerce_product_loop() ) {

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 *
			 * @hooked WC_Structured_Data::generate_product_data() - 10
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
