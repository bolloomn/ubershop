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
    add_menu_page('Бараа', 'Борлуулалтын тайлан', 'add_users', 'product_report', '_product_report', plugin_dir_url( __FILE__ ).'images/report.png', 4);
}
add_action('admin_menu', 'register_menu_page');

function _product_tatalt(){
    baraa_header();
   include 'include/baraa-tatalt.php';
    baraa_footer();
}

function checkPost($key){
    if(isset($_POST[$key])){
        return $_POST[$key];
    }
    return;
}

function _product_uldegdel(){
    baraa_header();
    if(isset($_GET['pid'])) {
        include "include/uldegdel_history.php";
    } else {
        include 'include/uldegdel.php';
    }
    baraa_footer();
}

function _product_report(){
    baraa_header();
    include 'include/report.php';
    baraa_footer();
}

function baraa_header(){
?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/bootstrap.min.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/select2.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/select2-bootstrap.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/select2-bootstrap.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/font-awesome/css/font-awesome.min.css" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/jquery.datetimepicker.css" rel="stylesheet">
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
?>
<?php
function baraa_footer(){
    ?>
    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/php-date-formatter.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/jquery.mousewheel.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ );?>js/jquery.datetimepicker.js"></script>
    <script type="text/javascript">
        $('.date').datetimepicker({
            // formatTime:'H:i',
            format:'Y-m-d H:i',
            lang:'mn',
            mask:'9999-19-39 29:59',
            minDate: '<?php echo date('Y-m-d 00:00', time()-60*60*24*30);?>',
            // value: new Date(),
        });
    </script>
<?php
}


function unit_price($id, $date){
    global $wpdb;
    $where="";
    if(!is_null($date)):
        $where=" AND date<'".$date."'";
    endif;
    return  $wpdb->get_var(
        "SELECT ifnull(round(sum(quantity*cost)/sum(quantity),4), 0) as price
                FROM trade_product_info
                where product_id=".$id.$where
    );
}

function itemtype($type){
    if($type==0){ return "хорогдсон"; }
    if($type==1){ return "татан авалт"; }
    if($type==2){ return "зарлагдсан"; }
    if($type==3){ return "буцаах бичилт"; }
    return;
}

function update_prices($id, $date){
    global $wpdb;
    $query= "SELECT *
             FROM trade_product_info
             where product_id=".$id."
             AND date>='".$date."'
             AND type!=1
             order by info.date asc
            ";
    $rows = $wpdb->get_results($query);

    if($rows){
        foreach ($rows as $row){
            $cost=unit_price($id, $row->date);
            if($cost!=$row->cost){
                $a=1;
                if($row->quantity<0){ $a=-1;  }
                $data=['cost'=>$cost, 'amount'=>$a*$cost*$row->quantity];
                $wpdb->update('trade_product_info', $data, ['id'=> $row->id], ['%s', '%s'], ['%d']);
            }
        }
    }
}

//remove_role( 'contributor' );
?>