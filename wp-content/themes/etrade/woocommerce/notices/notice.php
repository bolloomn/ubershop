<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! $messages ) return;
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info">
		<i class="fa fa-exclamation-circle"></i>
		<?php
			echo wc_kses_notice( $message );
		?>
	</div>
<?php endforeach; ?>