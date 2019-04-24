<?php
global  $wpdb;

    //config
    $link=home_url('wp-admin/admin.php?page=wallet_huselt&pages=');

    $sdate=date('Y-m-d 00:00');
    $fdate=date('Y-m-d 23:59');
    if(isset($_GET['sdate'])){   $sdate=$_GET['sdate'];}
    if(isset($_GET['fdate'])){   $fdate=$_GET['fdate'];}

    $user_query="";
    $sda_user=0;
    if(isset($_GET['user']) and $_GET['user']!=0){
        $user_query=" and huselt.user_id=".$_GET['user'];
        $sda_user=$_GET['user'];
    }

    $pages='&sdate='.$sdate.'&fdate='.$fdate.'&user='.$sda_user;

if(isset($_GET['id']) and isset($_GET['type'])){

        $huselt=$wpdb->get_row('select * from trade_woo_wallet_huselt where id='.$_GET['id']);
        $error='';
        if($huselt->status==0){
            $balance= getWallet($huselt->user_id)-$huselt->amount;
            //debit uurchluh

            if($_GET['type']==1 and $balance>=0){
                $data = [
                    'type' => 'debit',
                    'user_id' => $huselt->user_id,
                    'details' =>'Мөнгөн шилжүүлэг: '.$huselt->amount.'₮ '.$huselt->t_bank_name.', '.$huselt->t_bank_account.', '.$huselt->t_bank_number,
                    'amount' => $huselt->amount,
                    'balance' => getWallet($huselt->user_id)-$huselt->amount,
                ];
                $wpdb->insert('trade_woo_wallet_transactions', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);
                $wpdb->update('trade_woo_wallet_huselt', ['status' => 1], ['id'=> $_GET['id']], ['%s'], ['%d']);
            } else {
                $error='&r=1';
            }
            //status uurchluh
            if($_GET['type']==2){
                $wpdb->update('trade_woo_wallet_huselt', ['status' => 2], ['id'=> $_GET['id']], ['%s'], ['%d']);
            }

        }

        die('<meta http-equiv="refresh" content="0;URL=\''.$link.$pages.$error.'\'" /> ');
    }

     $query= "SELECT huselt.*, trade_users.user_login as username
             FROM trade_woo_wallet_huselt as huselt 
             left join trade_users 
             on trade_users.ID=huselt.user_id
             where huselt.created>='".$sdate."'
             and huselt.created<='".$fdate."'
             ".$user_query."
             order by huselt.created desc";

     $rows = $wpdb->get_results($query);

?>
<div class="wrap">
    <div class="row">
        <div class="col-lg-4">
            <h1 class="wp-heading-inline mb-lg-3">Мөнгө шилжүүлэх хүсэлт</h1>
        </div>
        <div class="col-lg-8 text-lg-right">

            <form method="get"  class="form-inline pull-right p-3 report-form">
                <input name="page" value="wallet_huselt" type="hidden">

                <input type="text" name="sdate" value="<?=$sdate;?>" style="width: 155px;" class="form-control date mb-2 mr-sm-2">
                <input type="text"  name="fdate" value="<?=$fdate;?>" style="width: 155px;" class="form-control date mb-2 mr-sm-2">

                <select  class="form-control select2" name="user" id="user"  >
                    <option value="0">Бүх хэрэглэгч</option>
                    <?php foreach (get_users() as $user){ ?>
                        <option value="<?php echo $user->ID; ?>" <?php if($sda_user==$user->ID){ echo 'selected';} ?>><?php echo $user->user_login; ?></option>
                    <?php } ?>
                </select>

                <button type="submit" class="btn btn-primary mb-2  mr-sm-2">Хайх</button>
                <button type="button" onclick="window.print();" class="btn btn-success mb-2">Хэвлэх</button>

            </form>

        </div>
    </div>


    <?php if(isset($_GET['r']) and $_GET['r']==1){ echo '<div  class="alert alert-danger mb-4" role="alert">Хэтэвчний үлдэгдэл хүрэлцэхгүй байна</div>'; } ?>

    <table class="bg-white table table-hover ">
        <thead>
            <tr class="bg-primary text-white">
                <th width="200">Огноо</th>
                <th>Хэрэглэгч</th>
                <th>Мөнгөн дүн</th>
                <th>Банк</th>
                <th>Эзэмшигч</th>
                <th>Дансны мэдэээл</th>
                <th width="100">Төлөв</th>
                <th width="187"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row->created; ?></td>
                <td><?php echo $row->username; ?></td>
                <td><?php echo $row->amount; ?>₮</td>
                <td><?php echo $row->t_bank_name; ?></td>
                <td><?php echo $row->t_bank_account; ?></td>
                <td><?php echo $row->t_bank_number; ?></td>
                <td>
                    <?php
                        if($row->status==0){
                            echo '<div class="badge badge-warning text-white p-1">Хүлээгдэж буй</div>';
                        } elseif($row->status==1) {
                            echo '<div class="badge badge-success text-white p-1">Илгээгдсэн</div>';
                        } else {
                            echo '<div class="badge badge-danger text-white p-1">Цуцлагдсан</div>';
                        }
                    ?>
                </td>
                <td>

                    <?php  if($row->status==0){ ?>
                    <a  class="btn btn-sm btn-outline-success"
                        href="<?php echo $link.$pages.'&id='.$row->id.'&type=1'; ?>"
                        onclick="return confirm('Та зөвшөөрөхдөө итгэлтэй байна уу!')"
                    ><i class="fa fa-check"></i> зөвшөөрөх</a>
                    <a class="btn btn-sm btn-outline-danger"
                       href="<?php echo $link.$pages.'&id='.$row->id.'&type=2'; ?>"
                       onclick="return confirm('Та цуцлахдаа итгэлтэй байна уу!')">
                        <i class="fa fa-remove"></i> цуцлах
                    </a>
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>



</div>


