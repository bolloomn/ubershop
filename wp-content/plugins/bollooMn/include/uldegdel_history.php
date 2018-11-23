<?php
    global $wpdb;
    $sdate=date('Y-m-d 00:00');
    $fdate=date('Y-m-d 23:59');
    if(isset($_GET['sdate'])){   $sdate=$_GET['sdate'];}
    if(isset($_GET['fdate'])){   $fdate=$_GET['fdate'];}
    $link=home_url('wp-admin/admin.php?page=product_uldegdel&sdate='.$sdate.'&fdate='.$fdate.'&pid='.$_GET['pid']);
    $title = $wpdb->get_var("SELECT post_title FROM trade_posts where ID=".$_GET['pid']);


$query = "select sum(quantity) as quantity,  sum(amount) as amount
          from trade_product_info 
          where date < '".$sdate."'
          AND product_id=".$_GET['pid'];

$start = $wpdb->get_row($query);

$query = "  SELECT info.type, info.cost, info.quantity, info.amount, info.content, info.date,  trade_users.user_login as username
        FROM trade_product_info as info
        join trade_users
        on trade_users.ID=info.user_id
        and date BETWEEN '".$sdate."' and '".$fdate."'
        and info.product_id=".$_GET['pid']."
        ORDER BY info.date ASC
          ";
$rows = $wpdb->get_results($query);
$start_amount=$start->amount;
$start_quantity=$start->quantity;
?>

<div class="wrap">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="wp-heading-inline mb-lg-3"><?php echo $title; ?>
                <a class="btn btn-primary btn-sm" href="<?php echo home_url('wp-admin/admin.php?page=product_uldegdel'); ?>">Буцах</a>
            </h1>
        </div>
        <div class="col-lg-6 text-lg-right">
            <form method="get"  class="form-inline pull-right p-3">
                <input name="page" value="product_uldegdel" type="hidden">
                <input name="pid" value="<?=$_GET['pid'];?>" type="hidden">
                <input type="text" name="sdate" value="<?=$sdate;?>" class="form-control date mb-2 mr-sm-2">
                <input type="text"  name="fdate" value="<?=$fdate;?>" class="form-control date mb-2 mr-sm-2">
                <button type="submit" class="btn btn-primary mb-2">Хайх</button>
            </form>
        </div>
    </div>

    <table class="bg-white table table-hover table-bordered ">
        <thead>
        <tr class="bg-secondary text-white">
            <th>Огноо</th>
            <th>Ажилтан</th>
            <th>Төлөв</th>
            <th>Хэмжээ</th>
            <th>Мөнгөн дүнгээр</th>
            <th>Эцсийн үлдэгдэл</th>
            <th>Нийт мөнгөн дүн</th>
            <th>Тайлбар</th>
        </tr>
        </thead>
        <tbody>
            <?php
                foreach ($rows as $row){
                    $start_amount +=$row->amount;
                    $start_quantity +=$row->quantity;
            ?>
            <tr>
                <td><?php echo $row->date; ?></td>
                <td><?php echo $row->username; ?></td>
                <td><?php echo itemtype($row->type); ?></td>
                <td><?php echo $row->quantity; ?></td>
                <td><?php echo number_format($row->amount, '2', '.', ''); ?> ₮</td>
                <td><?php echo $start_quantity; ?></td>
                <td><?php echo number_format($start_amount, '2', '.', ''); ?> ₮</td>
                <td><?php echo $row->content; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
