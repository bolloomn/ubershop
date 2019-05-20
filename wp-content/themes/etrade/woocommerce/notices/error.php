<?php
/**
 * Show error messages
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
<ul class="alert alert-danger" role="alert">
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<i class="fa fa-times"></i>
			<?php
				echo wc_kses_notice( $message );
			?>
		</li>
	<?php endforeach; ?>
</ul>
