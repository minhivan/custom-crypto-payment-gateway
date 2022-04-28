<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($this->description) {
    echo wpautop(wp_kses_post($this->description));
}
echo '<fieldset id="wc-' . esc_attr($this->id) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
?>
<div class="ct-text-block cryotopay-type-choose radio-choose">
    <div class="ct-text-block item">

        <label for="cryotopay_type_auto">
            <div class="ct-text-block image">
                <img alt="" src="/modules/invests/assets/img/logo-metamask.png" class="ct-image" loading="lazy" width="28" height="28">
            </div>
            <p class="ct-text-block"><?php esc_html_e('Metamask Pay', 'woocommerce'); ?></p>
            <input type="radio" value="auto" name="cryotopay_type" id="cryotopay_type_auto" checked>
        </label>
    </div>
    <div class="ct-text-block item">

        <label for="cryotopay_type_manual">
            <div class="ct-text-block image">
                <lord-icon target="div" src="/modules/invests/assets/lordicon/60-documents-outline-edited.json" trigger="hover" style="width:28px;height:28px"></lord-icon>
            </div>
            <p class="ct-text-block"><?php esc_html_e('Manual Pay', 'woocommerce'); ?></p>
            <input type="radio" value="manual" name="cryotopay_type" id="cryotopay_type_manual">
        </label>
    </div>
</div>
<?php
echo '<div class="clear"></div></fieldset>';
// $option_keys = array_keys($this->options);

// woocommerce_form_field( 'cryotopay_type', array(
//     'type'          => 'radio',
//     'class'         => array('cryotopay_type form-row-wide'),
//     'label'         => __('CryptoPay Types', $this->domain),
//     'options'       => $this->options,
// ), reset( $option_keys ) );
