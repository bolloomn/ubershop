<?php
global $wpdb;

$sdate=date('Y-m-d 00:00');
$fdate=date('Y-m-d 23:59');
if(isset($_GET['sdate'])){   $sdate=$_GET['sdate'];}
if(isset($_GET['fdate'])){   $fdate=$_GET['fdate'];}

$user_query="";
$sda_user=0;
if(isset($_GET['user']) and $_GET['user']!=0){
    $user_query=" and info.user_id=".$_GET['user'];
    $sda_user=$_GET['user'];
}


$seller_query="";
$sda_seller=0;
if(isset($_GET['seller']) and $_GET['seller']!=0){
    $seller_select=" seller.seller_name ";
    $seller_query=" and meta_value='".$_GET['seller']."' ";
    $sda_seller=$_GET['seller'];
}

$sda_tugeegch=0;
$tugeegch_query="";
if(isset($_GET['tugeegch']) and $_GET['tugeegch']!=0){
    $tugeegch_query=" and trade_users.ID=".$_GET['tugeegch'];
    $sda_tugeegch=$_GET['tugeegch'];
}

$link=home_url('wp-admin/admin.php?page=product_report&sdate='.$sdate.'&fdate='.$fdate);


 $select="
        SELECT info.cost, info.price, -1*info.quantity as quantity, info.date,  trade_users.user_login as username,  info.product_name, info.order_id, tugeelt.tugeelt_name as tugeegch, seller.seller_name 
        FROM trade_product_info as info
        join trade_users
        on trade_users.ID=info.user_id
        and date>='".$sdate."'
        and date<='".$fdate."'
        and info.type=0
        ".$user_query."
        join (
           select trade_postmeta.post_id as order_id, trade_users.user_login as tugeelt_name from  trade_users
           join trade_postmeta on meta_key='tugeegch' and trade_postmeta.meta_value=trade_users.ID 
           ".$tugeegch_query."
        ) tugeelt
        on info.order_id=tugeelt.order_id
        join ( 
        select ID from trade_posts
        where post_type='shop_order' and post_status='wc-completed'
        ) aa 
       
        on aa.ID=info.order_id
        join (
         select post_id, name as seller_name from  trade_postmeta
         join trade_terms on trade_postmeta.meta_value=trade_terms.term_id
         where meta_key='seller' ".$user_query."
         ) seller on seller.post_id=info.product_id
        ORDER BY info.date ASC
    ";
$rows=$wpdb->get_results($select);


?>

<div class="wrap">
    <div class="row">
        <div class="col-lg-3">
            <h1 class="wp-heading-inline mb-lg-3">Борлуулалтын тайлан</h1>
        </div>
        <div class="col-lg-9 text-lg-right">

            <form method="get"  class="form-inline pull-right p-3 report-form">
                <input name="page" value="product_report" type="hidden">

                    <input type="text" name="sdate" value="<?=$sdate;?>" style="width: 155px;" class="form-control date mb-2 mr-sm-2">
                    <input type="text"  name="fdate" value="<?=$fdate;?>" style="width: 155px;" class="form-control date mb-2 mr-sm-2">

                    <select  class="form-control select2" name="user" id="user"  >
                        <option value="0">Бүх хэрэглэгч</option>
                        <?php foreach (get_users() as $user){ ?>
                            <option value="<?php echo $user->ID; ?>" <?php if($sda_user==$user->ID){ echo 'selected';} ?>><?php echo $user->user_login; ?></option>
                        <?php } ?>
                    </select>

                    <select  class="form-control select2" name="tugeegch" id="tugeegch" >
                        <option value="0">Бүх түгээгч</option>
                        <?php foreach (get_users(['role__in'=>['tugeegch']]) as $user){ ?>
                            <option value="<?php echo $user->ID; ?>"  <?php if($sda_tugeegch==$user->ID){ echo 'selected';} ?>><?php echo $user->user_login; ?></option>
                        <?php } ?>
                    </select>

                    <select  class="form-control select2" name="tugeegch" id="tugeegch" >
                        <option value="0">Бүх түгээгч</option>
                        <?php foreach (get_users(['role__in'=>['tugeegch']]) as $user){ ?>
                            <option value="<?php echo $user->ID; ?>"  <?php if($sda_tugeegch==$user->ID){ echo 'selected';} ?>><?php echo $user->user_login; ?></option>
                        <?php } ?>
                    </select>
                    <?php $sellers=get_terms('product_tag', 'hide_empty=0');?>
                <select  class="form-control select2" name="seller" id="seller" >

                    <option value="0">Бүх борлуулагч</option>
                    <?php foreach ($sellers as $seller){ ?>
                        <option value="<?php echo $seller->term_id; ?>"  <?php if($sda_seller==$seller->term_id){ echo 'selected';} ?>><?php echo $seller->name; ?></option>
                    <?php } ?>
                </select>

                <button type="submit" class="btn btn-primary mb-2  mr-sm-2">Хайх</button>
                <button type="button" onclick="window.print();" class="btn btn-success mb-2">Хэвлэх</button>

            </form>

        </div>
    </div>
    <table class="bg-white table table-hover">
        <thead>
        <tr class="bg-primary text-white">
            <th>Захиалга</th>
            <th>Огноо</th>
            <th>Бараа</th>
            <th>Хэрэглэгч</th>
            <th>Хүргэсэн</th>
            <th>Үнэ</th>
            <th>Тоо</th>
            <th>Орлого</th>
<!--            <th>Өртөг</th>-->
<!--            <th>Ашиг</th>-->
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

//                $urtug=$row->cost*$row->quantity;
//                $niit_urtug +=$urtug;

                $ashig=($row->price-$row->cost)*$row->quantity;
                $niit_ashig += $ashig;
            ?>
            <tr>
                <td>#<?php echo $row->order_id;?></td>
                <td><?php echo $row->date;?></td>
                <td><?php echo $row->product_name;?></td>
                <td><?php echo $row->username;?></td>
                <td><?php echo $row->tugeegch;?></td>
                <td><?php echo $row->price;?></td>

<!--                <td>--><?php //echo $row->cost;?><!--</td>-->
                <td><?php echo $row->quantity;?></td>
                <td><?php echo number_format($orlogo, 2, '.', '');?> ₮</td>
<!--                <td>--><?php //echo number_format($urtug, 2, '.', '');?><!-- ₮</td>-->
<!--                <td>--><?php //echo number_format($orlogo, 2, '.', '');?><!-- ₮</td>-->
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="6" class="text-right">Нийт</th>
            <th><?php echo $niit_too;?></th>
            <th><?php echo number_format($niit_orlogo, 2, '.', '');?> ₮</th>
<!--            <th>--><?php //echo number_format($niit_urtug, 2, '.', '');?><!-- ₮</th>-->
<!--            <th>--><?php //echo number_format($niit_ashig, 2, '.', '');?><!-- ₮</th>-->
        </tfoot>
    </table>
</div>



