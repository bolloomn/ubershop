<?php
    //nevtresen esehiig shalgah
    if (!is_user_logged_in() or !isset($_GET['o'])){ wp_redirect(home_url()); die(); }

    // zahailgiin medeelliig avah  bas shalgah
    $order = _qpay_getOrderDetailById($_GET['o']);
    if($order['order']['status']=='on-hold'){ wp_redirect(home_url()); die(); }

    //төлбөр төлсөн эсэхийг шалгах
    if(isset($_GET['check']) and isset($_GET['o'])) {
       header('Content-Type: application/json');
       echo  _qpay_woo_CheckPayment();
       die();
    }

    //төлбөр төлсөн бол захиалга дуусгах
    if(isset($_GET['payment']) and isset($_GET['o'])) {
        $result=_qpay_woo_CheckPayment();
        $result=json_decode($result, true);
        if($result['result_code']=='0'){
            $order = wc_get_order( $_GET['o'] );
            $order->update_status( 'wc-on-hold', __( 'payment on-hold', 'wc-gateway-offline' ) );
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            wp_redirect( home_url('checkout/order-received/'.$_GET['o'].'/?key='.$order->order_key ));
        } else {
            wp_redirect(get_permalink( get_page_by_path( 'qpay-payment' ) ).'?o='.$_GET['o']);
        }
        die();
    }

    //qpay huselt yvuulah
    $qpay_data =_qpay_woo_CreateInvoice($_GET['o'], $order['order']['total']);
    if($qpay_data['result_code']!='0'){
        wp_redirect(home_url()); die();
    }

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <title>qpay цахим төлбөрийн систем</title>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . '../style/css/bootstrap.min.css'; ?>" >
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . '../style/css/style.css'; ?>" >

</head>

<body class="bg-light">
<div class="container">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="<?php echo plugin_dir_url( __FILE__ ) . '../style/images/logo.png'; ?>" alt=""  height="40">
        <h5>ЦАХИМ ТӨЛБӨРИЙН ҮЙЛЧИЛГЭЭ</h5>
        <p >Нэхэмжлэлийн мэдээллийг QR коджуулан хэрэглэгчдэд түгээх, хэрэглэгч интернэт банкны апп ашиглан QR код унших замаар
            ТӨЛБӨР ТӨЛӨХ, ТӨЛБӨР ХҮЛЭЭН АВАХ, ШИЛЖҮҮЛЭГ ХИЙХ боломжийг олгох үйлчилгээ юм.</p>
    </div>

    <div class="row">
        <div class="col-sm-6 order-sm-1 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Төлбөрийн мэдээлэл</span>
            </h4>
            <ul class="list-group mb-3">
                <?php
                if(count($order['order']['line_items'])>0):
                    foreach ($order['order']['line_items'] as $item):
                        echo '<li class="list-group-item d-flex justify-content-between bg-light">
                                        <div >
                                            <h6 class="my-0">'.$item['name'].'</h6>
                                            <small>'.$item['price'].'₮ х '.$item['quantity'].'</small>
                                        </div>
                                        <span class="text-muted">'.$item['total'].' ₮</span>
                                  </li>';
                    endforeach;
                endif;
                ?>
                <?php
                      if(count($order['order']['fee_lines'])>0):
                          foreach ($order['order']['fee_lines'] as $fee):
                            echo '<li class="list-group-item d-flex justify-content-between bg-light">
                                        <div class="text-success">
                                            <h6 class="my-0">'.$fee['title'].'</h6>
                                        </div>
                                        <span class="text-success">'.$fee['total'].' ₮</span>
                                  </li>';
                          endforeach;
                      endif;
                 ?>
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Нийт төлбөр</strong>
                    <strong><?=$order['order']['total']; ?> ₮</strong>
                </li>
            </ul>
        </div>
        <div class="col-sm-6 order-sm-2">
            <h4 class="mb-3">QR зураг</h4>
            <div class="text-center p-3 bg-white">
            <img  src="data:image/png;base64,<?php echo $qpay_data['json_data']['qPay_QRimage']; ?>">
            <p class="mt-1" >Хэрэв та QR код уншуулах боломжгүй <br>бол <a class="qrUrl" target="_blank" href="http://qpay.mn/q/?q=<?php echo $qpay_data['json_data']['qPay_QRcode'];?>">ЭНД ДАРНА УУ</a>.</p>
            </div>
            <a href="javascript:void(0)" id="finish" class="form-control btn  btn-primary mt-3">Дуусгах</a>
        </div>
       </div>
    </div>
</div>
<script src="<?php echo plugin_dir_url( __FILE__ ) . '../style/js/jquery-2.1.1.js'; ?>"></script>
<script>$( document ).ready(function() {$('#finish').click(function () {$.get( "<?=get_permalink( get_page_by_path( 'qpay-payment' ) ).'?o='.$_GET['o'].'&check=1'; ?>", function( data ) {if(data.result_code=='0'){window.location.href = "<?=get_permalink( get_page_by_path( 'qpay-payment' ) ).'?o='.$_GET['o'].'&payment=1'; ?>";} else {alert(data.result_msg);}});});});</script>
</body>




