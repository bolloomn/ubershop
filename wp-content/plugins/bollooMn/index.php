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
    baraa_footer();
}

function _product_uldegdel(){
    baraa_header();
    include 'include/uldegdel.php';
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
        $('#date').datetimepicker({
            // formatTime:'H:i',
            format:'Y-m-d H:i',
            lang:'mn',
            mask:'9999-19-39 29:59',
            value: new Date(),
        });
    </script>
<?php
}


function last_average_price($item, $date){
    if(!is_null($date)):
        $where=[" and item2_order.date<'".$date."' " ];
    else:
        $where=["", "", ""];
    endif;
    return $this->db
        ->query("
                    select if(sum(amount)/sum(size) is null, 0, round(sum(amount)/sum(size),6)) as price
                    from
                    (
                    select 
                    if(sum(item2_order_detail.quantity*item2_order_detail.factor) is null, 0, sum(item2_order_detail.quantity*item2_order_detail.factor)) as size,
                    if(sum(item2_order_detail.price*item2_order_detail.quantity) is null, 0, sum(item2_order_detail.price*item2_order_detail.quantity)) as amount
                    FROM item2_order
                    INNER JOIN item2_order_detail 
                    ON item2_order.id=item2_order_detail.item_order_id
                    AND item2_order.is_deleted=0
                    INNER JOIN item2
                    ON item2.id=item2_order_detail.item_id 
                    AND item2_order_detail.unit_minor=item2.minor
                    and item2.id=".$item."
                    ".$where[0]."
                    union all
                    select 
                    if(sum(item2_to_products.value*item2_to_products.quantity) is null, 0, sum(item2_to_products.value*item2_to_products.quantity))*(-1) as size, 
                    if(sum(item2_to_products.quantity*item2_to_products.unit_cost) is null, 0, sum(item2_to_products.quantity*item2_to_products.unit_cost))*(-1) as amount
                    from
                    item2_to_products
                    INNER JOIN item2
                    ON item2.id=item2_to_products.item_id 
                    AND item2_to_products.minor_id=item2.minor
                    and item2.id=".$item."
                    ".$where[1]."
                    union all 
                    select 
                    if(sum(item2_outgo.value) is null, 0, sum(item2_outgo.value))*(-1) as size,
                    if(sum(item2_outgo.amount) is null, 0, sum(item2_outgo.amount))*(-1) as amount
                    from item2_outgo 
                    inner join item2
                    on item2.id=item2_outgo.item_id 
                    and item2.minor=item2_outgo.minor_id
                    and item2_outgo.outgo_type!=1
                    and item2.id=".$item."
                    ".$where[2]."
                    ) item
                    ")->row()->price;
}
//remove_role( 'contributor' );
?>