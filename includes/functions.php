<?php
if (!defined('ABSPATH')) {
    exit();
}


/*
* function make request
* url : string | url request
* params : array | params request
*/
function makeApiRequest($url, $body)
{
    $response = wp_remote_post(CRYPTO_API . $url, array(
        'body'      => wp_json_encode($body),
        'method'    => 'POST',
        'timeout'   => 45,
        'sslverify' => false,
        'headers'   => [
            'content-type'      => 'application/json',
            'zomland-api-key'   => CRYPTO_API_KEY,
        ],
    ));


    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        return;
    } else {
        $result = json_decode(wp_remote_retrieve_body($response));
        return $result;
    }
}



function checkHealth()
{
    $response = wp_remote_get(CRYPTO_API . '/health', array(
        'timeout'   => 45,
        'sslverify' => false,
        'headers'   => [
            'content-type'      => 'application/json',
            'zomland-api-key'   => CRYPTO_API_KEY,
        ],
    ));
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        wc_add_notice(__('Error connection: ', 'woocommerce') . __($error_message, 'crypto_pay'), 'error');
        // logger
    } else {
        $result = json_decode(wp_remote_retrieve_body($response));
        wc_add_notice(__('Connection successful!', 'woocommerce'), 'success');
    }
}


function update_acf_meta($data)
{
    try {
        $data = (object) $data;

        if (!isset($data->id) || empty($data->id) || !isset($data->acf) || !count($data->acf))
            return false;

        foreach ($data->acf as $key => $value) {
            update_field($key, $value, $data->id);
        }

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function init_pay_for_order_form($order_id)
{
    $order  = new \WC_Order($order_id);
    $method = $_REQUEST['cryptopay_method'];
    $total  = floatval($order->get_total());

    if ($method == 'manual') {
        include_once CRYPTOPAY_PATH . 'templates/html/form-txn.php';
    } else {
        include_once CRYPTOPAY_PATH . 'templates/html/form-metamask.php';
    }
}

function crypto_pay_action_process()
{
    @ini_set('display_errors', 1);
    if (!wp_verify_nonce($_POST['nonce'], 'crypto-pay-nonce')) {
        wp_send_json_error([
            "status"    => "error",
            "error"     => true,
            "message"   => "Invalid nonce. Action prohibitied"
        ], 400);
    }

    if ((!isset($_POST['hash']) && empty($_POST['hash'])) || (!isset($_POST['order_id']) && empty($_POST['order_id']))) {
        wp_send_json_error([
            "status"    => "error",
            "error"     => true,
            "message"   => "Invalid body data"
        ], 400);
        wp_die();
    }

    $order_id       = $_POST['order_id'];
    $order          = new \WC_Order($order_id);
    $user_id        = $order->get_user_id();
    $user_wallet    = $_POST['user_wallet'];

    if ($order->get_status() != "pending") {
        wp_send_json_error([
            "status"    => "error",
            "error"     => true,
            "message"   => "Invalid order status"
        ], 400);
        wp_die();
    }

    // $hash_duplicate_check = count(call_api("GET", home_url() . "/wp-json/wc/v3/orders?owner_address=" . $wallet . "&hash=" . $_POST['hash']));
    // if ($hash_duplicate_check) {
    //    wp_send_json_success([
    //       'type' => false,
    //       "title" => __('Failed', 'custom'),
    //       "message" => __('Hash has been used in another order. Please recheck your hash', 'custom')
    //    ]);
    //    wp_die();
    // }
    
    if ($user_wallet) {
        update_field('_owner_address', $user_wallet, $order_id);
    }
    update_field('_transaction_hash', $_POST['hash'], $order_id);
    $order->update_status('wc-on-hold');

    wp_send_json_success([
        "status" => "success",
        "data" => array(
            "order_id" => $order_id
        )
    ], 200);

    wp_die();
}



function crypto_pay_order_process($order_id)
{
    $order              = new \WC_Order($order_id);
    $payment_method     = $order->get_payment_method();
    $order_status       = $order->get_status();

    if ($order_status === 'on-hold' && $payment_method === 'crypto_pay') {

        $body = [
            'order_id'  => strval($order_id),
            'env' => 'dev',
            'type' => 'topup'
        ];

        makeApiRequest('/order-check', $body);
    }
}
