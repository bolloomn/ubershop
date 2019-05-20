<?php

if( defined( 'ABSPATH') && defined('WP_UNINSTALL_PLUGIN') ) {

    //Remove the plugin's settings
    delete_option('_qpay_woo_register_menu_page');

}