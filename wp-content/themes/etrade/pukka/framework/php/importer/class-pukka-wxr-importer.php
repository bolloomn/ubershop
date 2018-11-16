<?php


class Pukka_WXR_Importer extends WXR_Importer {

	public function __construct( $options = array() ) {

		parent::__construct( $options );

		add_action( 'import_post_meta', array( $this, 'add_dynamic_meta' ), 10, 3 );

	}

	protected function post_exists( $data ) {
		return false;
	}

	public function add_dynamic_meta( $post_id, $key, $value) {

		if( '_pukka_dynamic_meta_box' === $key ) {
			global $pukka_dynamic_meta_data;

			if( !empty( $pukka_dynamic_meta_data[ $post_id ] ) ) {

				$data = base64_decode( $pukka_dynamic_meta_data[ $post_id ] );
				$data = json_decode( $data, true );
				$data = wp_slash( $data );

				update_post_meta( $post_id, $key, $data );
			}
		}

	}
}