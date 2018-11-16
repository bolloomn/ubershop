<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message" role="alert">
		<i class="fa fa-check"></i>
		<?php
			echo wc_kses_notice( $message );
		?>
	</div>
<?php endforeach; ?>
