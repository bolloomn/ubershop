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

$status=[
    'wc-pending'=>['<div class="badge badge-primary text-white p-1">Төлбөр хүлээгдэж байна</div>', 0],
    'wc-processing'=>['<div class="badge badge-warning text-white p-1">Боловсруулж байна</div>', 0],
    'wc-on-hold'=>['<div class="badge badge-warning text-white p-1">Хүлээгдэж байна</div>', 0],
    'wc-completed'=>['<div class="badge badge-success text-white p-1">Шийдвэрлэсэн</div>', 1],
    'wc-cancelled'=>['<div class="badge badge-danger text-white p-1">Цуцлагдсан</div>', 2],
    'wc-refunded'=>['<div class="badge badge-danger text-white p-1">Буцаагдсан</div>', 2],
    'wc-failed'=>['<div class="badge badge-danger text-white p-1">Амжилтгүй</div>', 2],
];

$limit=30;
$link=home_url('tugeelt?pages=');
$pages=1;
if(isset($_GET['pages'])){ if($_GET['pages']!=''){ $pages=$_GET['pages']; } }


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
    .table {
        font-size: 13px;
    }
</style>
<div id="content" class="clearfix">

    <div class="content-wrap">
        <div class="entry-content">
                <div class="bg-white p-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="heading-title">Миний түгээсэн</div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white">Огноо</th>

                                        <th class="text-white">дугаар</th>
                                        <th class="text-white">Мөнгөн дүн</th>
                                        <th class="text-white">Утас</th>
                                        <th class="text-white">Нэр</th>
                                        <th class="text-white">Хаяг</th>
                                        <th class="text-white">Төлөв</th>
                                        <th class="text-white">Төлөв</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($orders)) { ?>
                                        <?php foreach ($orders as $order){?>
                                            <tr>
                                                <td><?php echo $order->post_date; ?></td>
                                                <td>#<?php echo $order->ID; ?></td>
                                                <td><?php echo get_post_meta($order->ID, '_order_total', true); ?>₮</td>
                                                <td><?php echo get_post_meta($order->ID, 't_phone', true); ?></td>
                                                <td><?php echo get_post_meta($order->ID, 'last_name', true).' '.get_post_meta($order->ID, 'first_name', true); ?></td>
                                                <td><?php
                                                    echo get_post_meta($order->ID, 't_hot', true)
                                                        .', '.get_post_meta($order->ID, 't_sum', true)
                                                        .', '.get_post_meta($order->ID, 't_gudamj', true)
                                                        .', '.get_post_meta($order->ID, 't_bair', true)
                                                        .', '.get_post_meta($order->ID, 't_toot', true)
                                                        .'<br>'.get_post_meta($order->ID, 't_address', true);
                                                ?>
                                                </td>
                                                <td><?=$status[$order->post_status][0];?></td>
                                                <td>
                                                    <?php // if($status[$order->post_status][1]==0){ ?>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                               Засах
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                            </div>
                                                        </div>
                                                    <?php// } ?>
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

            <div class="mt-4">
                <nav >
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
            </div>

        </div><!-- .entry-content -->
    </div> <!-- .content-wrap -->

</div><!-- #content -->
<?php get_footer(); ?>

