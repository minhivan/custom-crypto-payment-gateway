<?php


// we need JavaScript to process a token only on cart/checkout pages, right?
if (!is_cart() && !is_checkout()) {
    return;
}

// if our payment gateway is disabled, we do not have to enqueue JS to  o
if ('no' === $this->enabled) {
    return;
}
wp_enqueue_script('cryptopay-main', CRYPTOPAY_URL . 'assets/js/validate.js', array('jquery'), '1.0.0', true);