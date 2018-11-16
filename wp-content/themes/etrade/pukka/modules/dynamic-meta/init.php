<?php
	define('DM_URI', PUKKA_URI .'/'. PUKKA_MODULES_DIR_NAME  .'/dynamic-meta');

	include_once('include/dynamic.meta.class.php');
    include_once('include/functions.php');

	// Set on which post types should page builder be used
	$use_on = array('page');
	if( 'on' == pukka_get_option('post_page_builder') ){
		$use_on[] = 'post';
	}


	// disable TinyMCE
//	if( 'on' == pukka_get_option('page_builder_tinymce_disable') ) :

		function dm_admin_inline_js(){
			echo "<script type='text/javascript'>\n";
			echo 'var PukkaDM = {tinyMCE: '. ('on' == pukka_get_option('page_builder_tinymce_disable') ? 'false' : 'true') .'};';
			echo "\n</script>";
		}
		add_action( 'admin_print_scripts', 'dm_admin_inline_js' );

//	endif;

	$dynamic_meta = new Dynamic_Meta($use_on); // Dynamic meta

