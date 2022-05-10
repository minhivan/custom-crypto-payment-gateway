<?php

// function crypto_pay_include_scripts()
// {

//    wp_enqueue_script('crypto-pay-main', CRYPTOPAY_PATH . 'assets/js/main.js', array(), false, false);

//    $information = array(
//       'ajaxurl' => admin_url('admin-ajax.php'),
//       'nonce' => wp_create_nonce('ivt-nonce'),
//       'url' => INVEST_URL,
//       'abi'            => get_field('abi_config', 'option'),
//       'master_address' => get_field('master_address', 'option'),
//       'amount' => 2000
//    );

//    wp_localize_script('crypto-pay-main', 'extradata', $information);
// }

// add_action('wp_enqueue_scripts', 'crypto_pay_include_scripts');
// add_action('admin_enqueue_scripts', 'crypto_pay_include_scripts');



if (!is_cart() && !is_checkout()) {
    return;
}

// if our payment gateway is disabled, we do not have to enqueue JS to  o
if ('no' === $this->enabled) {
    return;
}

wp_enqueue_script('cryptopay-main', CRYPTOPAY_URL . 'assets/js/main.js', array('jquery'), '1.0.0', true);

$extradata = array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ivt-nonce'),
    'url' => INVEST_URL,
    'abi'            => get_field('abi_config', 'option'),
    'master_address' => get_field('master_address', 'option'),
    'amount' => 2000
 );

 wp_localize_script('cryptopay-main', 'extradata', $extradata);
