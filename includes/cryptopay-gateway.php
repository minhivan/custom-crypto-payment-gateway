<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CryptoPayGateway')) {
    class CryptoPayGateway extends \WC_Payment_Gateway
    {
        /**
         * Class constructor, more about it in Step 3
         */
        public function __construct()
        {
            $this->id                   = 'crypto_pay'; // payment gateway plugin ID
            $this->domain               = 'teknix-cryptopay';
            $this->icon                 = ''; // URL of the icon that will be displayed on checkout page near your gateway name
            $this->has_fields           = false; // in case you need a custom credit card form
            $this->method_title         = 'Teknix CryptoPay Gateway';
            $this->method_description   = 'Pay with CryptoPay'; // will be displayed on the options page

            // gateways can support subscriptions, refunds, saved payment methods,
            // but in this tutorial we begin with simple payments
            $this->supports = array(
                'products'
            );

            // Define "payment type" radio buttons options field
            $this->options = array(
                'auto'      => __('Metamask Pay', $this->domain),
                'manual'    => __('Manual Pay', $this->domain),
            );

            // Method with all the options fields
            $this->init_form_fields();

            // Load the settings.
            $this->init_settings();
            $this->title        = $this->get_option('title');
            $this->description  = $this->get_option('description');
            $this->enabled      = $this->get_option('enabled');
            $this->manual       = 'yes' === $this->get_option('manual');
            $this->auto         = 'yes' === $this->get_option('auto');
            $this->crypto_main_wallet   = $this->get_option('crypto_main_wallet');
            $this->crypto_api_key       = $this->get_option('crypto_api_key');

            // This action hook saves the settings
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // We need custom JavaScript to obtain a token
            add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
            add_action('crypto_pay', 'init_pay_for_order_form', 1);


            // You can also register a webhook here
            // add_action( 'woocommerce_api_{webhook name}', array( $this, 'webhook' ) );
        }

        /**
         * Plugin options, we deal with it in Step 3 too
         */
        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable Teknix CryptoPay',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'no'
                ),
                'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'description' => 'This controls the title which the user sees during checkout.',
                    'default'     => 'CryptoPay',
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => 'Description',
                    'type'        => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default'     => 'Woocommerce payment gateway with crypto method',
                ),
                'crypto_main_wallet' => array(
                    'title'       => __('Main wallet', $this->domain),
                    'type'        => 'text',
                    'description' => __('Your main crypto wallet', $this->domain),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'manual' => array(
                    'title'       => 'Manual mode',
                    'label'       => 'Enable Manual Mode',
                    'type'        => 'checkbox',
                    'description' => 'Payment with transaction hash',
                    'default'     => 'no',
                ),
                'auto' => array(
                    'title'       => 'Auto mode',
                    'label'       => 'Enable Auto Mode',
                    'type'        => 'checkbox',
                    'description' => 'Payment with metamask',
                    'default'     => 'yes',
                ),
                'crypto_api_key' => array(
                    'title'       => 'API Key',
                    'type'        => 'password'
                )
            );
        }

        /**
         * You will need it if you want your custom credit card form, Step 4 is about it
         */
        public function payment_fields()
        {
            require_once CRYPTOPAY_PATH . 'templates/cryptopay-payment-fields.php';
        }

        /*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
        public function payment_scripts()
        {
            require_once CRYPTOPAY_PATH . 'includes/setup.php'; 
        }

        /**
         * Output for the order received page.
         */
        public function thankyou_page($order_id)
        {
        }

        /*
 		 * Fields validation, more in Step 5
		 */
        public function validate_fields()
        {
            require_once CRYPTOPAY_PATH . 'templates/cryptopay-validate-fields.php';
        }

        /*
		 * We're processing the payments here, everything about it is in Step 5
		 */
        public function process_payment($order_id)
        {
            global $woocommerce;
            $order              = new WC_Order($order_id);
            $crypto_method      = !empty($_POST['cryotopay_type']) ? sanitize_text_field($_POST['cryotopay_type']) : '';
            $default_wallet     = get_field('_default_wallet', 'user_' . $order->get_customer_id());
            $owner_address      = !empty($_POST['billing_wallet']) ? $_POST['billing_wallet'] : $default_wallet;
            $support_type = $this->options;

            if (!isset($support_type[$crypto_method])) {
                wc_add_notice(__('Wrong payment type:', 'woocommerce') . __('CryptoPay type does not support, please try again', 'mycred_pay'), 'error');
                return null;
            }

            // add metadata
            update_acf_meta([
                'id' => $order_id,
                'acf' => array(
                    '_order_type' => 'topup',
                    '_created_by' => 'checkout',
                    '_payment_via' => $this->id,
                    '_payment_type' => $crypto_method,
                    '_is_paid' => 0,
                    '_disabled' => 0,
                    '_owner_address' => $owner_address,
                )
            ]);

            // update order status
            $order->update_status('wc-pending', esc_html__('Payment is awaited.', $this->domain));
            // Remove cart
            $woocommerce->cart->empty_cart();

            $url = add_query_arg('cryptopay_method', $crypto_method, $order->get_checkout_payment_url());

            // Return thankyou redirect
            return array(
                'result' => 'success',
                'redirect' => $url
            );
        }

        /*
		 * In case you need a webhook, like PayPal IPN etc
		 */
        public function webhook()
        {
        }
    }
}
