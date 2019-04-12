<?php
/*
Plugin Name: woocommerce qpay mongolia
Plugin URI:
Description: qpay.mn
Version: 1.0
Author: Ankhbaatar
Author URI: https://www.facebook.com/Tuvshin.ankhbaatar
License: Public Domain
*/
defined( 'ABSPATH' ) or exit;

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}


function _qpay_woo_add_to_gateways( $gateways ) {
    $gateways[] = 'WC_Gateway_Qpay';
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', '_qpay_woo_add_to_gateways' );


function _qpay_woo_register_menu_page(){
    add_submenu_page('options-general.php', 'qpay', 'qpay', 'add_users', 'qpay', '_qpay_woo_settings');
}

add_action('admin_menu', '_qpay_woo_register_menu_page');

function _qpay_woo_settings(){
    include 'includes/settings.php';
}


add_filter( 'page_template', '_qpay_woo_page_template' );
function _qpay_woo_page_template( $page_template )
{
    if ( is_page( 'qpay-payment' ) ) {
        $page_template = dirname( __FILE__ ) . '/includes/qpay-template.php';
    }
    return $page_template;
}

/*test data*/
if(get_option('_qpay_woocommerce_settings') === false){
    $data=[
        'merchant_code'=>'TEST_MERCHANT',
        'merchant_verification_code'=>'s',
        'merchant_customer_code'=>'UB0007339',
        'invoice_code'=>'TEST_INVOICE',
        'username'=>'test_merchant',
        'password'=>'cC5v4nT5',
        'url'=>'http://202.5.205.79:8083/WebServiceQPayMerchant.asmx/',
    ];
    add_option('_qpay_woocommerce_settings', $data);
}

function _qpay_woo_restCall($fname, $request, $url=null, $username=null, $password=null) {

    if(is_null($username) and is_null($password)){
        $settings=get_option('_qpay_woocommerce_settings');
        $username=$settings['username'];
        $password=$settings['password'];
    }

    if(is_null($url)){
        $settings=get_option('_qpay_woocommerce_settings');
        $url=$settings['url'];
    }

    $curl = curl_init($url.$fname);

    $headers = array(
        'Content-Type:application/json',
        'Authorization: Basic ' . base64_encode($username.":".$password)
    );

    curl_setopt($curl, CURLOPT_URL, $url.$fname);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $curl_response = curl_exec($curl);

    $info = curl_getinfo($curl);
    if ($curl_response === false) {
        curl_close($curl);
        return "error " . $info;
    }
    curl_close($curl);

//    $curl_response = substr($curl_response, $info['header_size']);
//    $decoded       = json_decode($curl_response, true);

//    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
//        return $decoded->response->errormessage;
//    }
    return $curl_response;
}


function _qpay_woo_Test(){
     $data='
     {
          "type": "4",
          "merchant_code": "'.$_POST['qpay']['merchant_code'].'",
          "merchant_verification_code": "'.$_POST['qpay']['merchant_verification_code'].'",
          "merchant_customer_code": "'.$_POST['qpay']['merchant_customer_code'].'",
          "json_data": {
           "invoice_code": "'.$_POST['qpay']['invoice_code'].'",
           "merchant_branch_code": "1",
           "pos_id":"1",
           "merchant_invoice_number": "1511860263840",
           "invoice_date": "2017-11-28 09:11:03.840",
           "invoice_description": "Төлбөрийн нэхэмжлэх",
           "invoice_total_amount": "2000.00",
           "item_btuk_code":"8821",
           "vat_flag":"1"
         }
        }
     ';
    return _qpay_woo_restCall('qPay_genInvoiceSimple', $data, $_POST['qpay']['url'], $_POST['qpay']['username'],$_POST['qpay']['password']);
}


function _qpay_woo_CreateInvoice($order_id, $order_total){
    $settings=get_option('_qpay_woocommerce_settings');
    $request = '{
        "type": "4",
        "merchant_code": "'.$settings['merchant_code'].'",
        "merchant_verification_code": "'.$settings['merchant_verification_code'].'",
        "merchant_customer_code": "'.$settings['merchant_customer_code'].'",
        "json_data": {
            "operator_code": null,
            "invoice_code":"'.$settings['invoice_code'].'",
            "merchant_branch_code": "1",
            "merchant_invoice_number": "' .$order_id. '",
            "invoice_date": "'.date('Y-m-d H:i:s.'.substr((string)microtime(), 2, 3)).'",
            "invoice_description": "#'.$order_id.' дугаартай төлбөрийн нэхэмжлэх",
            "invoice_total_amount": "'.$order_total.'",
            "item_btuk_code": "623",
            "vat_flag": "0"
        }
    }';
    return  _qpay_woo_restCall("qPay_genInvoiceSimple", $request);
}



function _qpay_woo_CheckPayment(){
    $settings=get_option('_qpay_woocommerce_settings');
    $request = '{
    "type": "4",
    "merchant_code": "'.$settings['merchant_code'].'",
    "merchant_verification_code": "'.$settings['merchant_verification_code'].'",
    "json_data": {
        "lang_code":"MON",
        "operator_code": null,
        "invoice_code": "'.$settings['invoice_code'].'",
        "merchant_invoice_number": "'.$_GET['o'].'"
    }
}';
    $result = _qpay_woo_restCall("qPay_checkInvoicePayment2", $request);
    return json_encode($result);
}


function _qpay_woo_getInvoicePyaments(){
    $request = '{
        "type": "4",
        "merchant_code": "TEST_MERCHANT",
        "merchant_verification_code": "S",
        "json_data": {
            "lang_code":"MON",
            "operator": "OPERATOR"
        }
    }';
    $result = _qpay_woo_restCall("qPay_getInvoicePayments2", $request);
}



add_action( 'plugins_loaded', 'wc_offline_gateway_init', 11 );

function wc_offline_gateway_init() {
    class WC_Gateway_Qpay extends WC_Payment_Gateway {
        /**
         * Constructor for the gateway.
         */
        public function __construct() {

            $this->id                 = 'qpay';
            $this->icon               = apply_filters('woocommerce_offline_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'Qpay', 'wc-gateway-offline' );
            $this->method_description = __( 'Нэхэмжлэлийн мэдээллийг QR коджуулан хэрэглэгчдэд түгээх, хэрэглэгч интернэт банкны апп ашиглан QR код унших замаар ТӨЛБӨР ТӨЛӨХ, ТӨЛБӨР ХҮЛЭЭН АВАХ, ШИЛЖҮҮЛЭГ ХИЙХ боломжийг олгох үйлчилгээ юм.', 'wc-gateway-offline' );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title        = $this->get_option( 'title' );
            $this->description  = $this->get_option( 'description' );
            $this->instructions = $this->get_option( 'instructions', $this->description );

            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

            // Customer Emails
            add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
        }


        /**
         * Initialize Gateway Settings Form Fields
         */
        public function init_form_fields() {

            $this->form_fields = apply_filters( 'wc_offline_form_fields', array(

                'enabled' => array(
                    'title'   => __( 'Enable/Disable', 'wc-gateway-offline' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'qpay идэвхжүүлэх', 'wc-gateway-offline' ),
                    'default' => 'yes'
                ),

                'title' => array(
                    'title'       => __( 'Гарчиг', 'wc-gateway-offline' ),
                    'type'        => 'text',
                    'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'wc-gateway-offline' ),
                    'default'     => __( 'Qpay', 'wc-gateway-offline' ),
                    'desc_tip'    => true,
                ),

                'description' => array(
                    'title'       => __( 'Description', 'wc-gateway-offline' ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', 'wc-gateway-offline' ),
                    'default'     => __( '', 'wc-gateway-offline' ),
                    'desc_tip'    => true,
                ),

                'instructions' => array(
                    'title'       => __( 'Instructions', 'wc-gateway-offline' ),
                    'type'        => 'textarea',
                    'description' => __( 'Instructions that will be added to the thank you page and emails.', 'wc-gateway-offline' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
            ) );
        }


        /**
         * Output for the order received page.
         */
        public function thankyou_page() {
            if ( $this->instructions ) {
                echo wpautop( wptexturize( $this->instructions ) );
            }
        }


        /**
         * Add content to the WC emails.
         *
         * @access public
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
        public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

            if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
                echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
            }
        }


        /**
         * Process the payment and return the result
         *
         * @param int $order_id
         * @return array
         */
        public function process_payment( $order_id ) {

//            $order = wc_get_order( $order_id );

            // Mark as on-hold (we're awaiting the payment)
//            $order->update_status( 'on-hold', __( 'Awaiting offline payment', 'wc-gateway-offline' ) );

            // Reduce stock levels
//            $order->reduce_order_stock();

            // Remove cart
//            WC()->cart->empty_cart();

            // Return thankyou redirect
            return array(
                'result' 	=> 'success',
                'redirect'	=> get_permalink( get_page_by_path( 'qpay-payment' ) ).'?o='.$order_id
            );
        }

    } // end \WC_Gateway_Offline class
}




function _qpay_getOrderDetailById($id, $fields = null, $filter = array()) {
    if (is_wp_error($id))
        return $id;
    // Get the decimal precession
    $dp = (isset($filter['dp'])) ? intval($filter['dp']) : 2;
    $order = wc_get_order($id); //getting order Object

    if(!$order){ wp_redirect(home_url()); die(); }

    $user = wp_get_current_user();
    if($order->get_user_id()!=$user->ID){
        wp_redirect(home_url()); die();
    }

    $order_data = array(
        'id' => $order->get_id(),
        'order_number' => $order->get_order_number(),
        'created_at' => $order->get_date_created()->date('Y-m-d H:i:s'),
        'updated_at' => $order->get_date_modified()->date('Y-m-d H:i:s'),
        'completed_at' => !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '',
        'status' => $order->get_status(),
        'currency' => $order->get_currency(),
        'total' => wc_format_decimal($order->get_total(), $dp),
        'subtotal' => wc_format_decimal($order->get_subtotal(), $dp),
        'total_line_items_quantity' => $order->get_item_count(),
        'total_tax' => wc_format_decimal($order->get_total_tax(), $dp),
        'total_shipping' => wc_format_decimal($order->get_total_shipping(), $dp),
        'cart_tax' => wc_format_decimal($order->get_cart_tax(), $dp),
        'shipping_tax' => wc_format_decimal($order->get_shipping_tax(), $dp),
        'total_discount' => wc_format_decimal($order->get_total_discount(), $dp),
        'shipping_methods' => $order->get_shipping_method(),
        'order_key' => $order->get_order_key(),
        'payment_details' => array(
            'method_id' => $order->get_payment_method(),
            'method_title' => $order->get_payment_method_title(),
            'paid_at' => !empty($order->get_date_paid()) ? $order->get_date_paid()->date('Y-m-d H:i:s') : '',
        ),
        'billing_address' => array(
            'first_name' => $order->get_billing_first_name(),
            'last_name' => $order->get_billing_last_name(),
            'company' => $order->get_billing_company(),
            'address_1' => $order->get_billing_address_1(),
            'address_2' => $order->get_billing_address_2(),
            'city' => $order->get_billing_city(),
            'state' => $order->get_billing_state(),
            'formated_state' => WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()], //human readable formated state name
            'postcode' => $order->get_billing_postcode(),
            'country' => $order->get_billing_country(),
            'formated_country' => WC()->countries->countries[$order->get_billing_country()], //human readable formated country name
            'email' => $order->get_billing_email(),
            'phone' => $order->get_billing_phone()
        ),
        'shipping_address' => array(
            'first_name' => $order->get_shipping_first_name(),
            'last_name' => $order->get_shipping_last_name(),
            'company' => $order->get_shipping_company(),
            'address_1' => $order->get_shipping_address_1(),
            'address_2' => $order->get_shipping_address_2(),
            'city' => $order->get_shipping_city(),
            'state' => $order->get_shipping_state(),
            'formated_state' => WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()], //human readable formated state name
            'postcode' => $order->get_shipping_postcode(),
            'country' => $order->get_shipping_country(),
            'formated_country' => WC()->countries->countries[$order->get_shipping_country()] //human readable formated country name
        ),
        'note' => $order->get_customer_note(),
        'customer_ip' => $order->get_customer_ip_address(),
        'customer_user_agent' => $order->get_customer_user_agent(),
        'customer_id' => $order->get_user_id(),
        'view_order_url' => $order->get_view_order_url(),
        'line_items' => array(),
        'shipping_lines' => array(),
        'tax_lines' => array(),
        'fee_lines' => array(),
        'coupon_lines' => array(),
    );
    //getting all line items
    foreach ($order->get_items() as $item_id => $item) {
        $product = $item->get_product();
        $product_id = null;
        $product_sku = null;
        // Check if the product exists.
        if (is_object($product)) {
            $product_id = $product->get_id();
            $product_sku = $product->get_sku();
        }
        $order_data['line_items'][] = array(
            'id' => $item_id,
            'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $dp),
            'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
            'total' => wc_format_decimal($order->get_line_total($item, false, false), $dp),
            'total_tax' => wc_format_decimal($item['line_tax'], $dp),
            'price' => wc_format_decimal($order->get_item_total($item, false, false), $dp),
            'quantity' => wc_stock_amount($item['qty']),
            'tax_class' => (!empty($item['tax_class']) ) ? $item['tax_class'] : null,
            'name' => $item['name'],
            'product_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product->get_parent_id() : $product_id,
            'variation_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product_id : 0,
            'product_url' => get_permalink($product_id),
            'product_thumbnail_url' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail', TRUE)[0],
            'sku' => $product_sku,
            'meta' => wc_display_item_meta($item)
        );
    }
    //getting shipping
    foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
        $order_data['shipping_lines'][] = array(
            'id' => $shipping_item_id,
            'method_id' => $shipping_item['method_id'],
            'method_title' => $shipping_item['name'],
            'total' => wc_format_decimal($shipping_item['cost'], $dp),
        );
    }
    //getting taxes
    foreach ($order->get_tax_totals() as $tax_code => $tax) {
        $order_data['tax_lines'][] = array(
            'id' => $tax->id,
            'rate_id' => $tax->rate_id,
            'code' => $tax_code,
            'title' => $tax->label,
            'total' => wc_format_decimal($tax->amount, $dp),
            'compound' => (bool) $tax->is_compound,
        );
    }
    //getting fees
    foreach ($order->get_fees() as $fee_item_id => $fee_item) {
        $order_data['fee_lines'][] = array(
            'id' => $fee_item_id,
            'title' => $fee_item['name'],
            'tax_class' => (!empty($fee_item['tax_class']) ) ? $fee_item['tax_class'] : null,
            'total' => wc_format_decimal($order->get_line_total($fee_item), $dp),
            'total_tax' => wc_format_decimal($order->get_line_tax($fee_item), $dp),
        );
    }
    //getting coupons
    foreach ($order->get_items('coupon') as $coupon_item_id => $coupon_item) {
        $order_data['coupon_lines'][] = array(
            'id' => $coupon_item_id,
            'code' => $coupon_item['name'],
            'amount' => wc_format_decimal($coupon_item['discount_amount'], $dp),
        );
    }
    return array('order' => apply_filters('woocommerce_api_order_response', $order_data, $order, $fields));
}