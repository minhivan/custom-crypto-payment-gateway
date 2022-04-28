<?php
if (!defined('ABSPATH')) {
    exit();
}


/*
* function make request
* url : string | url request
* params : array | params request
*/
function makeApiRequest($url, $body, $config)
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
        var_export($result);
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
        // var_export($result);
        wc_add_notice(__('Connection successful!', 'woocommerce'), 'success');
    }
}


function sendMessageToServer($order_id, $config)
{
    $order              = new \WC_Order($order_id);
    $payment_method     = $order->get_payment_method();
    $order_status       = $order->get_status();

    if ($order_status === 'processing' && $payment_method === 'crypto_pay') {
        $body = [
            'order_id'  => strval($order_id),
            'env'       => 'dev',
        ];
        makeApiRequest('/invest', $body, $config);
    }
}

