<?php
 $message=false;
 if(isset($_POST['save'])){
    $check=_qpay_woo_Test();
    if(!is_null($check)){
        if($check['result_code']=='0'){
            $message=['class'=>'updated', 'text'=>'Амжилттай хадгаллаа'];
            update_option('_qpay_woocommerce_settings', $_POST['qpay']);
        } else {
            $message=['class'=>'error', 'text'=>'Таны оруулсан мэдээлэл алдаатай байна та мэдээллээ шалгаад дахин оруулна уу.'];
        }
    } else {
        $message=['class'=>'error', 'text'=>'Таны оруулсан мэдээлэл алдаатай байна та мэдээллээ шалгаад дахин оруулна уу.'];
    }
 }
 $qpay=get_option('_qpay_woocommerce_settings');
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Qpay тохиргоо</h1>
    <p>qpay.mn-ээс гаргаж өгсөн кодыг оруулна уу</p>

    <?php if($message): ?>
    <div id="message" class="<?=$message['class'];?> notice is-dismissible"><p><?=$message['text'];?></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Энэ мэдэгдлийг алгасах.</span></button>
    </div>
    <?php endif; ?>

    <form method="post">
        <table class="form-table">
            <tbody>
            <tr class="form-field">
                <th><label for="merchant_code">merchant code</label></th>
                <td><input name="qpay[merchant_code]" type="text" id="merchant_code" placeholder="Мерчантын код" required value="<?php if($qpay){ echo $qpay['merchant_code']; } ?>"></td>
            </tr>
            <tr class="form-field">
                <th><label for="merchant_verification_code">merchant verification code</label></th>
                <td><input name="qpay[merchant_verification_code]" type="text" id="merchant_verification_code" placeholder="Мерчантын түлхүүр үг" required value="<?php if($qpay){ echo $qpay['merchant_verification_code']; } ?>"></td>
            </tr>
            <tr class="form-field">
                <th><label for="merchant_customer_code">merchant customer code</label></th>
                <td><input name="qpay[merchant_customer_code]" type="text" id="merchant_customer_code" placeholder="Мерчантын хэрэглэгчийн дугаар" required value="<?php if($qpay){ echo $qpay['merchant_customer_code']; } ?>"></td>
            </tr>
            <tr class="form-field">
                <th><label for="invoice_code">invoice code</label></th>
                <td><input name="qpay[invoice_code]" type="text" id="invoice_code" placeholder="qPay нэхэмжлэхийн код" required value="<?php if($qpay){ echo $qpay['invoice_code']; } ?>"></td>
            </tr>
            <tr class="form-field">
                <th><label for="username">Username</label></th>
                <td><input name="qpay[username]" type="text" id="username" placeholder="Хэрэглэгчийн нэр" required value="<?php if($qpay){ echo $qpay['username']; } ?>"></td>
            </tr>
            <tr class="form-field">
                <th><label for="password">Password</label></th>
                <td><input name="qpay[password]" type="text" id="password" placeholder="Хэрэглэгчийн нууц үг" required value="<?php if($qpay){ echo $qpay['password']; } ?>"></td>
            </tr>
            <tr class="form-field">
                <th><label for="url">Qpay api url</label></th>
                <td><input name="qpay[url]" type="text" id="url" placeholder="Qpay api url" required value="<?php if($qpay){ echo $qpay['url']; } ?>"></td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="save"  class="button button-primary" value="Хадгалах"></p>
    </form>
</div>
