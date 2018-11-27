<?php
    global $wpdb;

    $sdate=date('Y-m-d 00:00');
    $fdate=date('Y-m-d 23:59');
    if(isset($_GET['sdate'])){   $sdate=$_GET['sdate'];}
    if(isset($_GET['fdate'])){   $fdate=$_GET['fdate'];}
    $link=home_url('wp-admin/admin.php?page=product_report&sdate='.$sdate.'&fdate='.$fdate);


     $select="
        SELECT info.cost, info.price, -1*info.quantity as quantity, info.date,  trade_users.user_login as username,  product.product_name
        FROM trade_product_info as info
        join trade_users
        on trade_users.ID=info.user_id
        and date>='".$sdate."'
        and date<='".$fdate."'
        and info.type=0
        join (
            select ID, post_title as product_name 
            from trade_posts
            where trade_posts.post_type='product'
        ) product 
        on product.ID=info.product_id
        join ( 
        select ID from trade_posts
        where post_type='shop_order' and post_status='wc-completed'
        ) aa 
        on aa.ID=info.order_id
        ORDER BY info.date ASC
    ";
    $rows=$wpdb->get_results($select);

?>

<div class="wrap">
    <div class="row">
        <div class="col-lg-4">
            <h1 class="wp-heading-inline mb-lg-3">Борлуулалтын тайлан</h1>
        </div>
        <div class="col-lg-8 text-lg-right">
            <form method="get"  class="form-inline pull-right p-3">
                <input name="page" value="product_report" type="hidden">
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
            <th>Бараа</th>
            <th>Борлуулагч</th>
            <th>Үнэ</th>
            <th>Өртөг</th>
            <th>Тоо</th>
            <th>Орлого</th>
            <th>Өртөг</th>
            <th>Ашиг</th>
        </tr>
        </thead>
        <tbody>
            <?php
            $niit_too=0;
            $niit_orlogo=0;
            $niit_urtug=0;
            $niit_ashig=0;
            foreach ($rows as $row){
                $niit_too +=$row->quantity;

                $orlogo=$row->price*$row->quantity;
                $niit_orlogo +=$orlogo;

                $urtug=$row->cost*$row->quantity;
                $niit_urtug +=$urtug;

                $ashig=($row->price-$row->cost)*$row->quantity;
                $niit_ashig += $ashig;
            ?>
            <tr>
                <td><?php echo $row->date;?></td>
                <td><?php echo $row->product_name;?></td>
                <td><?php echo $row->username;?></td>
                <td><?php echo $row->price;?></td>
                <td><?php echo $row->cost;?></td>
                <td><?php echo $row->quantity;?></td>
                <td><?php echo number_format($orlogo, 2, '.', '');?> ₮</td>
                <td><?php echo number_format($urtug, 2, '.', '');?> ₮</td>
                <td><?php echo number_format($orlogo, 2, '.', '');?> ₮</td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="5" class="text-right">Нийт</th>
            <th><?php echo $niit_too;?></th>
            <th><?php echo number_format($niit_orlogo, 2, '.', '');?> ₮</th>
            <th><?php echo number_format($niit_urtug, 2, '.', '');?> ₮</th>
            <th><?php echo number_format($niit_ashig, 2, '.', '');?> ₮</th>
        </tfoot>
    </table>
</div>



