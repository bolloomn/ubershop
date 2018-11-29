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
    add_menu_page('Мөнгө шилжүүлэх хүсэт', 'Мөнгө шилжүүлэх хүсэлт', 'add_users', 'wallet_huselt', '_wallet_send', plugin_dir_url( __FILE__ ).'images/report.png',5 );
}
add_action('admin_menu', 'register_menu_page');

function _product_tatalt(){
    baraa_header();
   include 'include/baraa-tatalt.php';
    baraa_footer();
}


function _wallet_send(){
    baraa_header();
    include 'include/wallet_send.php';
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
        $where=" AND info.date<'".$date."'";
    endif;
    return  $wpdb->get_var(
        "SELECT ifnull(round(sum(quantity*cost)/sum(quantity),4), 0) as price
                FROM trade_product_info as info
                left join trade_posts 
                on trade_posts.ID=info.order_id
                where  
                info.product_id=".$id." 
                AND (info.type!=0 or (info.type=0 and trade_posts.post_status='wc-completed' ))
                ".$where
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
             FROM trade_product_info as info
                left join trade_posts 
                on trade_posts.ID=info.order_id
                where  product_id=".$id." and (info.type>1 or (info.type=0 and trade_posts.post_status='wc-completed'))
                AND info.date>='".$date."'
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

function getWallet($user_id){
    global $wpdb;
     $query= "SELECT balance FROM trade_woo_wallet_transactions where user_id=".$user_id." and deleted=0 order by transaction_id desc limit 1";
    return  $wpdb->get_var($query);
}

function calcCash($order_id, $user_id){
    global $wpdb;

    $user_data=get_userdata( $user_id );
    $amount= get_post_meta($order_id, '_order_total', true);
    $key =get_post_meta($order_id, '_order_key', true);
    $link= home_url('checkout/order-received/'.$order_id.'/?key='.$key);
    $cash=round($amount/20,  PHP_ROUND_HALF_DOWN);

    //uuriin uramshuulal;
    $data = [
        'type' => 'credit',
        'user_id' => $user_id,
        'link' => $link,
        'details' =>$amount.'₮-ны 5%  урамшуулал: '.$cash.'₮ (Захиалгын дугаар #'.$order_id.')',
        'amount' => $cash,
        'balance' => getWallet($user_id)+$cash,
    ];
    $wpdb->insert('trade_woo_wallet_transactions', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);
    clear_woo_wallet_cache( $user_id );

    //naiziin uramshuulal
    $naiziin_id=get_user_meta($user_id, 't_parent', true);
    if($naiziin_id){

        $data = [
            'type' => 'credit',
            'user_id' => $naiziin_id,
            'link' => $link,
            'details' =>'Таны найз '.$user_data->user_login.'-ийн  '.$amount.'₮-ны 5%  урамшуулал: '.$cash.'₮ (Захиалгын дугаар #'.$order_id.')',
            'amount' => $cash,
            'balance' => getWallet($naiziin_id)+$cash
        ];
        $wpdb->insert('trade_woo_wallet_transactions', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);

        //naiziin  naiziin uramshuulal
        $naiziin_naiziin_id=get_user_meta($naiziin_id, 't_parent', true);
        if($naiziin_naiziin_id){
            $cash=round($amount/50,PHP_ROUND_HALF_DOWN);
            $naiziin_data=get_userdata( $naiziin_id );
            $data = [
                'type' => 'credit',
                'user_id' => $naiziin_naiziin_id,
                'link' => $link,
                'details' =>'Таны найз '.$naiziin_data->user_login.'-ийн найз '.$user_data->user_login.'-ийн '.$amount.'₮-ны 2%  урамшуулал: '.$cash.'₮ (Захиалгын дугаар #'.$order_id.')',
                'amount' =>$cash,
                'balance' => getWallet($naiziin_naiziin_id)+$cash
            ];
            $wpdb->insert('trade_woo_wallet_transactions', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);
        }
    }

}

function bolloomn_to_table($order_id){

    global $wpdb;
    $order=get_post($order_id);
    $user_id= get_post_meta($order_id, '_customer_user', true);
    $query= "SELECT order_item_id FROM trade_woocommerce_order_items where order_id=".$order_id;
    $items = $wpdb->get_results($query);
    foreach ($items as $item) {

        $date = date('Y-m-d H:i:s');
        $product_id = wc_get_order_item_meta($item->order_item_id, '_product_id', true);
        $qty = wc_get_order_item_meta($item->order_item_id, '_qty', true);
        $price = wc_get_order_item_meta($item->order_item_id, '_line_subtotal', true) / $qty;
        $cost = unit_price($product_id, $date);
        $amount = $cost * $qty;

        $data = [
            'type' => '0',
            'user_id' =>$user_id,
            'product_id' => $product_id,
            'date' => $date,
            'price' => $price,
            'cost' => $cost,
            'quantity' => -1*$qty,
            'amount' => -1*$amount,
            'order_id' => $order_id,
        ];

        $wpdb->insert('trade_product_info', $data, ['%s', '%s', '%s', '%s', '%s', '%s', '%s']);
    }

    //calc uramshuulal
    calcCash($order_id,$user_id);

}

function getHuselt($user_id){
    global $wpdb;
    $query = "SELECT ifnull(sum(amount),0)  FROM `trade_woo_wallet_huselt` where status=0 and user_id=" . $user_id;
    return $wpdb->get_var($query);
}

function userTree($user_id, $level=0){
    global $wpdb;
    $query= "SELECT trade_users.user_login, trade_users.ID  FROM trade_usermeta 
                join trade_users
                on trade_users.ID=trade_usermeta.user_id
                and meta_key='t_parent'
                and meta_value=".$user_id;
    $users = $wpdb->get_results($query);
    if(count($users)>0){
        echo '<ul>';
            $level++;
            foreach ($users as $user){
                echo '<li>'.$user->user_login;
                        if($level<=4){
                            userTree($user->ID, $level);
                        }
                echo '</li>';
            }
        echo '</ul>';
    }
}
//remove_role( 'contributor' );
?>