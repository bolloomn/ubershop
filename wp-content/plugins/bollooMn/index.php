<?php 
/*
Plugin Name: Хэрэгтэй код
Plugin URI: 
Description: Хэрэгтэй код
Version: 1.0
Author: Ankhbaatar
Author URI: https://www.facebook.com/Tuvshin.ankhbaatar
License: Public Domain
*/


/* change admin style */
function load_admin_style() {
        wp_register_style( 'admin_css', plugin_dir_url( __FILE__ ) . 'css/colors.min.css', false, '1.0.0' );
        wp_enqueue_style( 'admin_css', plugin_dir_url( __FILE__ ) . 'css/colors.min.css', false, '1.0.0' );
		wp_register_script('admin_js', plugin_dir_url( __FILE__ ) . 'js/data.js', false, '1.0.0', false );
		wp_enqueue_script('admin_js', plugin_dir_url( __FILE__ ) . 'js/data.js', false, '1.0.0', false );
}
add_action( 'admin_enqueue_scripts', 'load_admin_style' );



/* change admin style */
function my_admin_theme_style() {
    wp_enqueue_style('my-admin-theme', plugin_dir_url( __FILE__ ) .'css/login.css');
}
add_action('login_enqueue_scripts', 'my_admin_theme_style');




function register_menu_page() {
    add_menu_page('Бараа', 'Бараа таталт', 'add_users', 'product_tatalt', '_product_tatalt', plugin_dir_url( __FILE__ ).'images/baraa.png', 3);
    add_menu_page('Бараа', 'Барааны үлдэгдэл', 'add_users', 'product_uldegdel', '_product_uldegdel', plugin_dir_url( __FILE__ ).'images/uldegdel.png', 4);
    add_menu_page('Бараа', 'Барааны тайлан', 'add_users', 'product_report', '_product_report', plugin_dir_url( __FILE__ ).'images/report.png', 4);
}
add_action('admin_menu', 'register_menu_page');

function _product_tatalt(){
    baraa_header();
   include 'include/baraa-tatalt.php';
}

function _product_uldegdel(){
    baraa_header();
    include 'include/uldegdel.php';
}

function _product_report(){
    baraa_header();
    include 'include/report.php';
}

function baraa_header(){
?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/bootstrap.min.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/select2.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/select2-bootstrap.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/select2-bootstrap.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/font-awesome/css/font-awesome.min.css" >

    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/jquery-2.1.1.js" ></script>
    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/select2.full.min.js" ></script>
    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/popper.min.js" ></script>
    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/bootstrap.min.js" ></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //select2
            $('.select2').select2({ width: '100%' });

            $('select').on('select2:open', function(e){
                $('.select2-dropdown').parent().css('z-index', 9999);
            });
        });
    </script>
<?php
}
//remove_role( 'contributor' );
?>