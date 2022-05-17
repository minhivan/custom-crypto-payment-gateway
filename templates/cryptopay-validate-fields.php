<?php


if (!is_cart() && !is_checkout()) {
    return;
}

// if our payment gateway is disabled, we do not have to enqueue JS to  o
if ('no' === $this->enabled) {
    return;
}

$user_id = get_current_user_id();

if (empty($_POST['billing_first_name'])) {
    wc_add_notice('<strong>First name is required!</strong>', 'error');
    return false;
}

if (get_field('_default_wallet', 'user_' . $user_id) === null) {
    wc_add_notice('<strong>Wallet not found. Please enable wallet and submit again.</strong>', 'error');
    return false;
}

return true;