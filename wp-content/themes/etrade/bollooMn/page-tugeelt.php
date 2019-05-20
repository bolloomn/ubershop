<?php /* Template Name: my-tugeelt */
global $wpdb;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
    die();
}

$user = wp_get_current_user();

if($user->roles[0]!='tugeegch'){
   wp_redirect(home_url());
   die();
}


$limit=30;
$link=home_url('tugeelt?pages=');
$pages=1;
if(isset($_GET['pages'])){ if($_GET['pages']!=''){ $pages=$_GET['pages']; } }
$amount=get_post_meta('5032', 'tugeelt', true);

if(isset($_GET['id']) and isset($_GET['type'])){
    if($_GET['type']==1){

        $data = [
            'type' => 'credit',
            'user_id' => $user->ID,
            'details' =>'Хүргэлт: захиалгын дугаар #'.$_GET['id'],
            'amount' => $amount,
            'balance' => getWallet($user->ID)+$amount,
        ];
        $wpdb->insert('trade_woo_wallet_transactions', $data, ['%s', '%s', '%s', '%s', '%s']);

        $wpdb->update('trade_posts', ['post_status' => 'wc-completed'], ['ID'=> $_GET['id']], ['%s'], ['%d']);
    } elseif($_GET['type']==2){
        $wpdb->update('trade_posts', ['post_status' => 'wc-refunded'], ['ID'=> $_GET['id']], ['%s'], ['%d']);
    }
    wp_redirect($link.$pages);
}

$status=[
    'wc-pending'=>['<div class="badge badge-primary text-white p-2 mb-1">Төлбөр хүлээгдэж байна</div>', 0],
    'wc-processing'=>['<div class="badge badge-warning text-white p-2 mb-1">Боловсруулж байна</div>', 0],
    'wc-on-hold'=>['<div class="badge badge-warning text-white p-2 mb-1">Хүлээгдэж байна</div>', 0],
    'wc-completed'=>['<div class="badge badge-success text-white p-2 mb-1">Шийдвэрлэсэн</div>', 1],
    'wc-cancelled'=>['<div class="badge badge-danger text-white p-2 mb-1">Цуцлагдсан</div>', 2],
    'wc-refunded'=>['<div class="badge badge-danger text-white p-2 mb-1">Буцаагдсан</div>', 2],
    'wc-failed'=>['<div class="badge badge-danger text-white p-2 mb-1">Амжилтгүй</div>', 2],
];




$allpage = $wpdb->get_var("SELECT CEIL(count(0)/".$limit.")  
        FROM trade_postmeta as meta
        join trade_posts as post
        on post.id=meta.post_id
        and meta.meta_key='tugeegch'
        and meta.meta_value=".$user->ID);

$previous=1;
if($pages>1){ $previous=$pages-1; }

$next=$allpage;
if($pages<$allpage){ $next=$pages+1; }


$query= "SELECT post.post_date, post.post_status, post.ID, post.post_title 
         FROM trade_postmeta as meta
         join trade_posts as post
         on post.id=meta.post_id
         and meta.meta_key='tugeegch'
         and meta.meta_value=".$user->ID."
         order by post.post_date desc
         limit ".($pages-1).", ".$limit;

$orders = $wpdb->get_results($query);
?>

<?php get_header(); ?>

<style>
    .table { font-size: 13px; }
</style>
<div id="content" class="clearfix">

    <div class="content-wrap">
        <div class="entry-content">
                <div class="bg-white p-4">
                    <div class="row">
                        <div class="col-lg-12">
                          <div class="heading-title">Миний хүргэлт <small class="pull-right">Хүргэлт тутмаас таны хэтэвчинд <?php echo  $amount; ?>₮ нэмэгдэх болно.</small></div>
                        </div>


                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white">Захиалга</th>
                                        <th class="text-white">Мэдээлэл</th>
                                        <th class="text-white">Төлөв</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($orders)) { ?>
                                        <?php foreach ($orders as $order){?>
                                            <tr>
                                                <td>
                                                    Огноо: <?php echo $order->post_date; ?><br>
                                                    Захиалга: #<?php echo $order->ID; ?><br>
                                                    Төлбөрийн хэлбэр: <?php echo get_post_meta($order->ID, '_payment_method_title', true); ?><br>
                                                    Мөнгөн дүн: <?php echo get_post_meta($order->ID, '_order_total', true); ?>₮
                                                </td>
                                                <td >
                                                    Утас: <?php echo get_post_meta($order->ID, 't_phone', true); ?><br>
                                                    Нэр: <?php echo get_post_meta($order->ID, 'last_name', true).' '.get_post_meta($order->ID, 'first_name', true); ?><br>
                                                    Хаяг: <?php
                                                    echo get_post_meta($order->ID, 't_hot', true)
                                                        .', '.get_post_meta($order->ID, 't_sum', true)
                                                        .', '.get_post_meta($order->ID, 't_gudamj', true)
                                                        .', '.get_post_meta($order->ID, 't_bair', true)
                                                        .', '.get_post_meta($order->ID, 't_toot', true)
                                                        .'<br>'.get_post_meta($order->ID, 't_address', true);
                                                ?>
                                                </td>
                                                <td>
                                                    <?=$status[$order->post_status][0];?><br>
                                                    <?php  if($status[$order->post_status][1]==0){ ?>
                                                        <div class="dropdown mb-1">
                                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Засах
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" href="<?php echo $link.$pages.'&id='.$order->ID.'&type=1';?>"  onclick="return confirm('Шийдвэрлэсэн төлөвт шилжүүлэх үү!')">Шийдвэрлэсэн</a>
                                                                <a class="dropdown-item" href="<?php echo $link.$pages.'&id='.$order->ID.'&type=2';?>"  onclick="return confirm('Буцаагдсан төлөвт шилжүүлэх үүу!')">Буцаагдсан</a>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    <?php } ?>
                                                    <a target="_blank" class="btn btn-info btn-sm text-white" href="<?php  echo home_url('checkout/order-received/'.$order->ID.'/?key='.get_post_meta($order->ID, '_order_key', true ));?>">Дэлгэрэнгүй</a>
                                                </td>


                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4">Хоосон байна</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <nav class="mt-4" >
                    <ul class="pagination" style="list-style: none;">
                        <li class="page-item m-0">
                            <a class="page-link" href="<?=$link.$previous;?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php for ($page=1; $page<=$allpage; $page++){ ?>
                            <li class="page-item <?php if($page==$pages){ echo 'active'; }?> m-0"><a class="page-link" href="<?php echo $link.$page; ?>"><?php echo $page;?></a></li>
                        <?php }; ?>
                        <li class="page-item m-0">
                            <a class="page-link" href="<?=$link.$next;?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>


        </div><!-- .entry-content -->
    </div> <!-- .content-wrap -->

</div><!-- #content -->
<?php get_footer(); ?>

