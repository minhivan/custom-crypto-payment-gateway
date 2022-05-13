<?php

if (!is_cart() && !is_checkout()) {
    return;
}

// if our payment gateway is disabled, we do not have to enqueue JS to  o
if ('no' === $this->enabled) {
    return;
}
wp_enqueue_script('cryptopay-popup', CRYPTOPAY_URL . 'assets/js/crypto-popup.js', array('jquery'), '1.0.0', true);
wp_enqueue_script('cryptopay-main', CRYPTOPAY_URL . 'assets/js/crypto-main.js', array('jquery'), '1.0.0', true);


if (is_wc_endpoint_url('order-pay')) {
    wp_enqueue_script('metamask', INVEST_URL . 'assets/js/metamask.js', array('jquery'), null, true);
    $order          = wc_get_order(get_query_var('order-pay'));
    $payment_status = $order->get_status();
    $user_wallet    = get_field('_owner_address', $order->id);
    $extradata = array(
        'ajaxurl'           => admin_url('admin-ajax.php'),
        'nonce'             => wp_create_nonce('crypto-pay-nonce'),
        'url'               => CRYPTOPAY_URL,
        'receiving_wallet'  => get_field('_receiving_wallet', 'option'),
        'amount'            => $order->get_total(),
        'order_id'          => $order->id,
        'order_status'      => $payment_status,
        'order_key'         => $order->get_order_key(),
        'exchange_rate'     => get_field('_exchange_rate', 'option') ?? "1",
        'user_wallet'       => $user_wallet,
        'master_abi'        => get_field('_withdraw_master_abi', 'option') ?? [],
        'contract_address'  => get_field('_withdraw_contract_address', 'option') ?? "",
    );
    wp_localize_script('cryptopay-main', 'extraCryptoPay', $extradata);
}
