<?php
/*
 * Plugin Name: Teknix Crypto Pay 
 * Plugin URI: https://teknix.vn
 * Description: Payment gateway base on Cryptocurrency 
 * Author: Minh P.
 * Author URI: https://github.com/minhivan
 * Version: 1.0.1
 */


if (!defined('ABSPATH'))
    exit;




if (!class_exists('TeknixCryptoPay')) {
    class TeknixCryptoPay
    {

        /**
         * Constructor
         */
        public function __construct()
        {
        }



        // register all hooks
        public function registers()
        {
            add_action('plugins_loaded', array($this, 'crypto_pay_load_files'));
            add_filter('woocommerce_payment_gateways', array($this, 'teknix_cryptopay_add_gateway'));

            add_action('wp_ajax_crypto_pay_action_process', 'crypto_pay_action_process');
            add_action('wp_ajax_nopriv_crypto_pay_action_process', 'crypto_pay_action_process');
            
            add_action('woocommerce_order_status_on-hold', 'crypto_pay_order_process');
        }


        /*** Load required files */
        public function crypto_pay_load_files()
        {
            if (!class_exists('WooCommerce')) {
                add_action('admin_notices', array($this, 'crypto_pay_missing_notice'));
                return;
            }

            define('CRYPTOPAY_FILE', __FILE__);
            define('CRYPTOPAY_PATH', plugin_dir_path(CRYPTOPAY_FILE));
            define('CRYPTOPAY_URL', plugin_dir_url(CRYPTOPAY_FILE));

            if (!defined('BACKGROUND_API')) {
                define('BACKGROUND_API', 'https://beta-api.teknix.vn/api/v1');
            }
            if (!defined('BACKGROUND_API_KEY')) {
                define('BACKGROUND_API_KEY', get_field("_api_key", "option"));
            }
            define('MAIN_WALLET', '');


            /*** Include payment gateway */
            require_once CRYPTOPAY_PATH . 'includes/functions.php';
            require_once CRYPTOPAY_PATH . 'includes/constants.php';
            require_once CRYPTOPAY_PATH . 'includes/cryptopay-gateway.php';
        }

        /**
         * Add gateway
         *
         */
        public function teknix_cryptopay_add_gateway($gateways)
        {
            $gateways[] = 'CryptoPayGateway'; // your class name is here
            return $gateways;
        }

        public function crypto_pay_missing_notice()
        {
            if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                echo '<div class="error"><p>' . __('Cryptocurrency Payments Using MetaMask For WooCommerce requires WooCommerce to be active', 'cpmw') . '</div>';
            }
        }
    }
}


$crypto_pay = new TeknixCryptoPay();
$crypto_pay->registers();