<?php
global  $wpdb;

    //config
    $limit=30;
    $link=home_url('wp-admin/admin.php?page=wallet_huselt&pages=');
    $pages=1;
    if(isset($_GET['pages'])){ if($_GET['pages']!=''){ $pages=$_GET['pages']; } }

    if(isset($_GET['id']) and isset($_GET['type'])){

        $huselt=$wpdb->get_row('select * from trade_woo_wallet_huselt where id='.$_GET['id']);
        if($huselt->status==0){
            //debit uurchluh
            if($_GET['type']==1){
                $data = [
                    'type' => 'debit',
                    'user_id' => $huselt->user_id,
                    'details' =>'Мөнгөн шилжүүлэг: '.$huselt->amount.'₮ '.$huselt->t_bank_name.', '.$huselt->t_bank_account.', '.$huselt->t_bank_number,
                    'amount' => $huselt->amount,
                    'balance' => getWallet($huselt->user_id)-$huselt->amount,
                ];
                $wpdb->insert('trade_woo_wallet_transactions', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);
            }
            //status uurchluh
            $wpdb->update('trade_woo_wallet_huselt', ['status' => $_GET['type']], ['id'=> $_GET['id']], ['%s'], ['%d']);
        }
        die('<meta http-equiv="refresh" content="0;URL=\''.$link.$pages.'\'" /> ');
    }

    /*huselt*/
    $allpage = $wpdb->get_var(
                "SELECT CEIL(count(0)/".$limit.") 
                       FROM trade_woo_wallet_huselt as huselt 
                       inner join trade_users 
                       on trade_users.ID=huselt.user_id"
            );

    $previous=1;
    if($pages>1){ $previous=$pages-1; }

    $next=$allpage;
    if($pages<$allpage){ $next=$pages+1; }

    $query= "SELECT huselt.*, trade_users.user_login as username
                       FROM trade_woo_wallet_huselt as huselt 
                       inner join trade_users 
                       on trade_users.ID=huselt.user_id
             order by huselt.created desc
             limit ".($pages-1).", ".$limit;

     $rows = $wpdb->get_results($query);

?>
<div class="wrap">
    <h1 class="wp-heading-inline mb-lg-3">Мөнгө шилжүүлэх хүсэлт</h1>


    <table class="bg-white table table-hover ">
        <thead>
            <tr class="bg-primary text-white">
                <th width="200">Огноо</th>
                <th>Хэрэглэгч</th>
                <th>Мөнгөн дүн</th>
                <th>Банк</th>
                <th>Эзэмшигч</th>
                <th>Дансны дугаар</th>
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


                    <a  class="btn btn-sm btn-outline-success"
                        href="<?php echo $link.$pages.'&id='.$row->id.'&type=1'; ?>"
                        onclick="return confirm('Та зөвшөөрөхдөө итгэлтэй байна уу!')"
                    ><i class="fa fa-check"></i> зөвшөөрөх</a>
                    <a class="btn btn-sm btn-outline-danger"
                       href="<?php echo $link.$pages.'&id='.$row->id.'&type=2'; ?>"
                       onclick="return confirm('Та цуцлахдаа итгэлтэй байна уу!')">
                        <i class="fa fa-remove"></i> цуцлах
                    </a>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-4">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="<?=$link.$previous;?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php for ($page=1; $page<=$allpage; $page++){ ?>
                    <li class="page-item <?php if($page==$pages){ echo 'active'; }?>"><a class="page-link" href="<?php echo $link.$page; ?>"><?php echo $page;?></a></li>
                <?php }; ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$link.$next;?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</div>


