<?php
/**
 *
 * @package   Pukka Custom posts
 * @author    Pukka <office@pukkathemes.com>
 * @license   GPL-2.0+
 * @link      http://pukkathemes.com
 * @copyright 2014 Puka Themes
 *
 * @wordpress-plugin
 * Plugin Name:       Pukka Custom Posts
 * Plugin URI:        http://pukkathemes.com
 * Description:       Initilize theme's custom post types.
 * Version:           1.0.1
 * Author:            Pukka
 * Author URI:        http://pukkathemes.com
 * Text Domain:       pukka
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/**
* Framework is loaded this way so framework, from theme's folder, gets loaded (if it is there)
* 
*/
add_action( 'after_setup_theme', 'pukka_content_init' );
function pukka_content_init(){

    // Framework constants
    if( !defined('PUKKA_VERSION') ) define('PUKKA_VERSION', '0.7.0');
    if( !defined('PUKKA_FRAMEWORK_DIR') ) define('PUKKA_FRAMEWORK_DIR', plugin_dir_path(__FILE__) .'pukka/framework');
    if( !defined('PUKKA_FRAMEWORK_URI') ) define('PUKKA_FRAMEWORK_URI', plugin_dir_path(__FILE__) .'pukka/framework');
    if( !defined('PUKKA_OPTIONS_NAME') ) define('PUKKA_OPTIONS_NAME', 'pukka_options');
    if( !defined('PUKKA_POSTMETA_PREFIX') ) define('PUKKA_POSTMETA_PREFIX', '_pukka_');


	include_once( PUKKA_FRAMEWORK_DIR . '/pukka_init.php' );
	include_once( plugin_dir_path(__FILE__)  . 'content/content_init.php' );
}