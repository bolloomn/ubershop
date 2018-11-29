<?php /* Template Name: my-cash */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
}
$user = wp_get_current_user();

$wallet = getWallet($user->ID);


$max = round($wallet / 5 * 4) - getHuselt($user->ID);
$min = 1000;


if(isset($_POST['send'])){
    $amount=$_POST['amount'];
    if($amount<=$max and $min<=$amount){
        $data = [
            'status' => '0',
            'user_id' =>$user->ID,
            'amount' => $amount,
            't_bank_name' => $_POST['t_bank_name'],
            't_bank_account' => $_POST['t_bank_account'],
            't_bank_number' => $_POST['t_bank_number'],
        ];
        $wpdb->insert('trade_woo_wallet_huselt', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);
        wp_redirect('wallet');
        die();
    }

}


?>

<?php get_header(); ?>


<div id="content" class="clearfix">

    <div class="content-wrap">
        <div class="entry-content">
            <div class="row">

                <div class="col-lg-8 ">
                    <div class="bg-white p-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="heading-title">МИНИЙ ХЭТЭВЧ <span class="pull-right"><?php echo $wallet; ?> ₮</span></div>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white">Огноо</th>
                                        <th class="text-white">Тайлбар</th>
                                        <th class="text-white">Орлого</th>
                                        <th class="text-white">Зарлага</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $transactions = get_wallet_transactions(); ?>
                                    <?php if (!empty($transactions)) { ?>
                                        <?php foreach ($transactions as $transaction) :
                                            $credit = '-';
                                            $debit = '-';
                                            $transaction->type == 'credit' ? $credit = '+' . $transaction->amount . '₮' : $debit = '-' . $transaction->amount . '₮';
                                            ?>
                                            <tr>
                                                <td><?php echo $transaction->date; ?></td>
                                                <td><?php echo $transaction->details; ?></td>
                                                <td><?= $credit; ?></td>
                                                <td><?= $debit; ?></td>

                                            </tr>
                                        <?php endforeach; ?>
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
                <div class="col-lg-4 ">
                    <div class="bg-white p-4">
                        <?php if ($max >= $min) {?>
                            <div class="heading-title">Мөнгө авах
                                <span class="small pull-right">боломжит дүн: <?php echo $max; ?> ₮</span>
                            </div>
                            <form class="bg-white mb-4" method="post">
                                <p>
                                    <label for="dun">Шилжүүлэх мөнгөн дүн</label>
                                    <input type="number" max="<?php echo $max; ?>" min="<?php echo $min; ?>"
                                           name="amount" id="amount" class="form-control" value="" required>
                                </p>
                                <p>
                                    <label for="t_bank_name">Банк</label>
                                    <input type="text" id="t_bank_name" name="t_bank_name" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_bank_name', true); ?>">
                                </p>
                                <p>
                                    <label for="t_bank_account">Данс эзэмшигч</label>
                                    <input type="text" id="t_bank_account" name="t_bank_account" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_bank_account', true); ?>">
                                </p>
                                <p>
                                    <label for="t_bank_number">Дансны дугаар</label>
                                    <input type="number" id="t_bank_number" name="t_bank_number" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_bank_number', true); ?>">
                                </p>
                                <p>
                                    <input type="submit" name="send" class="form-control" value="хүcэлт илгээх">
                                </p>
                            </form>
                        <?php } ?>
                        <div class="heading-title">Мөнгө шилжүүлэх хүсэлтүүд</div>
                        <div>
                            <table class="table table-hover ">
                            <?php
                                $query = "SELECT *  FROM trade_woo_wallet_huselt where  user_id=".$user->ID." order by id desc";
                                $huseltuud=$wpdb->get_results($query);

                                function status($status){
                                    if($status==0){
                                        return '<span class="badge text-white badge-warning">Хүлээгдэж буй</span>';
                                    }
                                    if($status==1){
                                        return '<span class="badge text-white badge-success">Илгээгдсэн</span>';
                                    }
                                    if($status==2){
                                        return '<span class="badge text-white badge-danger">Цуцлагдсан</span>';
                                    }
                                }

                                foreach ($huseltuud as $h){

                            ?>

                                    <tr>
                                        <td>
                                            <?php echo  $h->created. '<br><small>'. $h->t_bank_name.', '.$h->t_bank_account.', '.$h->t_bank_number.'</small>'; ?>
                                        </td>
                                        <td class="text-center"><?=$h->amount;?>₮</td>
                                        <td class="text-center"><?=status($h->status);?></td>
                                    </tr>

                            <?php } ?>
                            </table>
                        </div>
                    </div>


                </div>

            </div>
        </div><!-- .entry-content -->
    </div> <!-- .content-wrap -->

</div><!-- #content -->
<?php get_footer(); ?>

