<?php
if (!defined('ABSPATH')) {
    exit();
}
if (!class_exists('CryptoPayment')) {
    class CryptoPayment
    {
        private $api_key = "";
        private $main_wallet = "";

        public function __construct($config = [])
        {
            $this->api_key      = isset($config['api_key']) ? $config['api_key'] : "";
            $this->main_wallet  = isset($config['main_wallet']) ?  $config['main_wallet'] : "";
        }

        public function init_pay_for_order_form($order_id)
        {
            $order = new \WC_Order($order_id);
            $method = $_GET['method'];
            if ($method == 'manual') {
                include_once CRYPTOPAY_PATH . 'templates/txnid-form.php';
            } else {
                include_once CRYPTOPAY_PATH . 'templates/metamask-form.php';
            }
        }

        public function crypto_pay_payment_verify()
        {
            @ini_set('display_errors', 1);
            if (!wp_verify_nonce($_POST['nonce'], 'ivt-nonce')) {
                die('Busted!');
            }

            if ((!isset($_POST['hash']) && empty($_POST['hash'])) || (!isset($_POST['order_id']) && empty($_POST['order_id']))) {
                wp_send_json_success('Wrong data');
                die();
            }

            $order_id       = $_POST['order_id'];
            $order          = new \WC_Order($order_id);
            $user_id        = $order->get_user_id();


            if ($order->get_status() != "pending") {
                wp_send_json_success([
                    'type' => true,
                    'id' => $order_id,
                    "title" => __('Failed', 'custom'),
                    "messageTitle" => __("Payment failed", 'custom'),
                    "message" => __('This order is Processing', 'custom')
                ]);
                die();
            }

            $default_wallet = get_user_meta($user_id, '_default_wallet', true);
            if (!$default_wallet) {
                wp_send_json_success([
                    'type' => false,
                    "title" => __('Failed', 'custom'),
                    "messageTitle" => __("No wallet detect", 'custom'),
                    "message" => __('No wallet found. Please add wallet and try again !', 'custom')
                ]);
                die();
            }
            
            // $hash_duplicate_check = count(call_api("GET", home_url() . "/wp-json/wc/v3/orders?owner_address=" . $wallet . "&hash=" . $_POST['hash']));
            // if ($hash_duplicate_check) {
            //    wp_send_json_success([
            //       'type' => false,
            //       "title" => __('Failed', 'custom'),
            //       "message" => __('Hash has been used in another order. Please recheck your hash', 'custom')
            //    ]);
            //    die();
            // }

            
            update_post_meta($order_id, '_transaction_hash', $_POST['hash']);
            $order->update_status('wc-on-hold');

            wp_send_json_success([
                'type' => true,
                'id' => $order_id,
                "title" => __('Payment Confirmation', 'custom'),
                "messageTitle" => __("Thank you", 'custom'),
                "message" => __("We have received your TXN ID. Payment checking will take around 30 minutes. Please wait patienly.", "custom"),
            ], 200);

            die();
        }

        public function crypto_pay_order_process($order_id)
        {
            $order              = new \WC_Order($order_id);
            $payment_method     = $order->get_payment_method();
            $order_status       = $order->get_status();

            if ($order_status === 'processing' && $payment_method === 'crypto_pay') {
                
                $config = [
                    'api-key' => $this->api_key,
                ];

                $body = [
                    'order_id'  => strval($order_id),
                    'env' => 'dev',
                ];

                makeApiRequest('/invest', $body, $config);
            }
        }
    }
}
