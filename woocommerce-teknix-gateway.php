<?php
/*
 * Plugin Name: WooCommerce Teknix Gateway
 * Plugin URI: https://teknix.vn
 * Description: Payment gateway base on Cryptocurrency 
 * Author: Minh P.
 * Author URI: https://github.com/minhivan
 * Version: 1.0.1
 */


if (!defined('ABSPATH'))
    exit;

// Make sure WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}


/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter('woocommerce_payment_gateways', 'teknix_add_gateway_class');
function teknix_add_gateway_class($gateways)
{
    $gateways[] = 'WC_Teknix_Gateway'; // your class name is here
    return $gateways;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action('plugins_loaded', 'teknix_init_gateway_class');
function teknix_init_gateway_class()
{

    class WC_Teknix_Gateway extends WC_Payment_Gateway
    {

        /**
         * Class constructor, more about it in Step 3
         */
        public function __construct()
        {
            $this->id = 'teknix'; // payment gateway plugin ID
            $this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
            $this->has_fields = true; // in case you need a custom credit card form
            $this->method_title = 'Crypto Gateway';
            $this->method_description = 'Description of Teknix payment gateway'; // will be displayed on the options page

            // gateways can support subscriptions, refunds, saved payment methods,
            // but in this tutorial we begin with simple payments
            $this->supports = array(
                'products'
            );

            // Method with all the options fields
            $this->init_form_fields();

            // Load the settings.
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->enabled = $this->get_option('enabled');
            $this->manual = 'yes' === $this->get_option('manual');
            $this->metamask = 'yes' === $this->get_option('metamask');

            // $this->private_key = $this->testmode ? $this->get_option('test_private_key') : $this->get_option('private_key');
            // $this->publishable_key = $this->testmode ? $this->get_option('test_publishable_key') : $this->get_option('publishable_key');

            // This action hook saves the settings
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // We need custom JavaScript to obtain a token
            add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));


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
                    'label'       => 'Enable Teknix Gateway',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'no'
                ),
                'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'description' => 'This controls the title which the user sees during checkout.',
                    'default'     => 'Cryptocurrency',
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => 'Description',
                    'type'        => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default'     => 'Pay with your transaction hash via our Cryptocurrency payment gateway.',
                ),
                'instructions' => array(
                    'title'       => __('Instructions', 'wc-teknix-gateway'),
                    'type'        => 'textarea',
                    'description' => __('Instructions that will be added to the thank you page and emails.', 'wc-teknix-gateway'),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'main-wallet' => array(
                    'title'       => __('Main wallet', 'wc-teknix-gateway'),
                    'type'        => 'text',
                    'description' => __('Your main crypto wallet', 'wc-teknix-gateway'),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'manual' => array(
                    'title'       => 'Manual mode',
                    'label'       => 'Enable Manual Mode',
                    'type'        => 'checkbox',
                    'description' => 'Place the payment gateway with manual transaction',
                    'default'     => 'no',
                ),
                'metamask' => array(
                    'title'       => 'Metamask mode',
                    'label'       => 'Enable Metamask Mode',
                    'type'        => 'checkbox',
                    'description' => 'Place the payment gateway with metamask transaction',
                    'default'     => 'yes',
                ),
                'api-key' => array(
                    'title'       => 'API Key',
                    'type'        => 'password'
                ),
            );
        }

        /**
         * You will need it if you want your custom credit card form, Step 4 is about it
         */
        public function payment_fields()
        {
            // ok, let's display some description before the payment form
            if ($this->description) {
                // you can instructions for test mode, I mean test card numbers etc.
                if ($this->testmode) {
                    $this->description  = trim($this->description);
                }
                // display the description with <p> tags etc.  
                echo wpautop(wp_kses_post($this->description));
            }

            // I will echo() the form, but you can close PHP tags and print it directly in HTML
?>
            <fieldset id="wc-' . esc_attr($this->id) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">
                <?php
                // Add this action hook if you want your custom payment gateway to support it
                do_action('woocommerce_credit_card_form_start', $this->id);

                ?>
                <ul>
                    <?php if ($this->get_option('manual')) { ?>
                        <li>
                            <input id="teknix_payment_manual" type="radio" class="input-radio" name="teknix_payment_method" value="manual" checked="checked" data-order_button_text="">
                            <div class="form-row form-row-first">
                                <label>Transaction id <span class="required">*</span></label>
                                <input id="teknix_crypto_txn" type="text" autocomplete="off" placeholder="0x...">
                            </div>
                            <div class="clear"></div>
                        </li>
                    <?php }
                    if ($this->get_option('metamask')) { ?>
                        <li>
                            <input id="teknix_payment_metamask" type="radio" class="input-radio" name="teknix_payment_method" value="metamask" data-order_button_text="">
                            <div class="form-row form-row-first">
                                <label>Pay with metamask<span class="required">*</span></label>
                                <!-- <input id="teknix_crypto_txn" type="text" autocomplete="off" placeholder="0x..."> -->
                            </div>
                            <div class="clear"></div>
                        </li>
                    <?php } ?>
                </ul>
    <?php

            do_action('woocommerce_credit_card_form_end', $this->id);

            echo '<div class="clear"></div></fieldset>';
        }

        /*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
        public function payment_scripts()
        {
            // we need JavaScript to process a token only on cart/checkout pages, right?
            if (!is_cart() && !is_checkout() && !isset($_GET['pay_for_order'])) {
                return;
            }

            // if our payment gateway is disabled, we do not have to enqueue JS too
            if ('no' === $this->enabled) {
                return;
            }

            // no reason to enqueue JavaScript if API keys are not set
            if (empty($this->private_key) || empty($this->publishable_key)) {
                return;
            }

            // do not work with card detailes without SSL unless your website is in a test mode
            if (!$this->testmode && !is_ssl()) {
                return;
            }

            // // let's suppose it is our payment processor JavaScript that allows to obtain a token
            // wp_enqueue_script('misha_js', 'https://www.mishapayments.com/api/token.js');

            // // and this is our custom JS in your plugin directory that works with token.js
            // wp_register_script('woocommerce_misha', plugins_url('misha.js', __FILE__), array('jquery', 'misha_js'));

            // // in most payment processors you have to use PUBLIC KEY to obtain a token
            // wp_localize_script('woocommerce_misha', 'misha_params', array(
            //     'publishableKey' => $this->publishable_key
            // ));

            // wp_enqueue_script('woocommerce_misha');
        }

        /**
         * Output for the order received page.
         */
        public function thankyou_page()
        {
            if ($this->instructions) {
                echo wpautop(wptexturize($this->instructions));
            }
        }

        /*
 		 * Fields validation, more in Step 5
		 */
        public function validate_fields()
        {
            if (empty($_POST['billing_first_name'])) {
                wc_add_notice('First name is required!', 'error');
                return false;
            }
            return true;
        }

        /*
		 * We're processing the payments here, everything about it is in Step 5
		 */
        public function process_payment($order_id)
        {
            global $woocommerce;

            try {
                $order = new WC_Order($order_id);
                $settings_obj = get_option('cpmw_settings');
                $crypto_currency = !empty($_POST['cpmw_crypto_coin']) ? sanitize_text_field($_POST['cpmw_crypto_coin']) : '';
                $total = $order->get_total();
                $type = $settings_obj['currency_conversion_api'];
                $in_crypto = $this->cpmw_price_conversion($total, $crypto_currency, $type);
                $user_wallet = $settings_obj['user_wallet'];
                $order->add_meta_data('cpmw_in_crypto', $in_crypto);
                $order->add_meta_data('cpmw_currency_symbol', $crypto_currency);
                $order->add_meta_data('cpmw_user_wallet', $user_wallet);
                $order->save_meta_data();
                $order->update_status($this->default_status);
                $woocommerce->cart->empty_cart();
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order),
                );
            } catch (Exception $e) {
                wc_add_notice(__('Payment error:', 'cpmw') . 'Unknown coin', 'error');
                return null;
            }
            wc_add_notice(__('Payment error:', 'woocommerce') . __('Payment could not be processed, please try again', 'cpmw'), 'error');
            return null;
        }

        /*
		 * In case you need a webhook, like PayPal IPN etc
		 */
        public function webhook()
        {
        }
    }
}
