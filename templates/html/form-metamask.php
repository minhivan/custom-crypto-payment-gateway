<div class="ct-div-block txn">
    <div class="ct-div-block info-qr" style="width: 100%;">
        <p class="ct-div-block body__1"><?php esc_html_e('Zomland information', 'woocommerce'); ?></p>
        <div class="ct-div-block info" style="width: 100%;">
            <div class="form-animation">
                <div class="form-row form-group validate-required other" id="address_field" data-priority="10">
                    <span class="label"><?php esc_html_e('Zomland Wallet Address', 'woocommerce'); ?></span>
                    <span class="text key-code"><?= get_field('_receiving_wallet', 'option'); ?></span>
                    <div class="line"></div>
                    <span class="copy key-copy-btn">
                        <img alt="" src="<?php echo INVEST_URL ?>assets/icons/copy-primary.svg" class="ct-image" loading="lazy" width="20" height="20">
                    </span>
                    <span class="success-key-copy" style="display: none;"><?php esc_attr_e('Copied to clipboard', 'woocommerce'); ?></span>
                    <i class="ic">
                        <lord-icon target="p" src="<?php echo INVEST_URL ?>assets/lordicon/179-email-book-outline-edited.json" trigger="click" style="width:20px;height:20px"></lord-icon>
                    </i>
                </div>
                <div class="form-row form-group validate-required" id="network_field" data-priority="10">
                    <input type="text" class="input-text form-control" name="network" id="network" value="BSC (BEP20)" placeholder="<?php esc_html_e('Network', 'woocommerce'); ?>" disabled>
                    <label for="network" class=""><?php esc_html_e('Network', 'woocommerce'); ?></label>
                    <div class="line"></div>
                    <i class="ic">
                        <img alt="" src="<?php echo INVEST_URL ?>assets/img/logo-binance.png" class="ct-image" loading="lazy">
                    </i>
                </div>
                <div class="form-row form-group validate-required" id="currency_field" data-priority="10">
                    <input type="text" class="input-text form-control" name="currency" id="currency" value="USDT" placeholder="<?php esc_html_e('Crypto currency', 'woocommerce'); ?>" disabled>
                    <label for="currency" class=""><?php esc_html_e('Crypto currency', 'woocommerce'); ?></label>
                    <div class="line"></div>
                    <i class="ic">
                        <img alt="" src="<?php echo INVEST_URL ?>assets/img/logo-usdt.png" class="ct-image" loading="lazy"></a>
                    </i>
                </div>
                <div class="form-row form-group validate-required" id="total_field" data-priority="10">
                    <input type="text" class="input-text form-control" name="total" id="total" value="<?= number_format_i18n($total, 2); ?> USDT" placeholder="<?php esc_html_e('Total', 'woocommerce'); ?>" disabled>
                    <label for="total" class=""><?php esc_html_e('Total', 'woocommerce'); ?></label>
                    <div class="line"></div>
                    <i class="ic">
                        <img alt="" src="<?php echo INVEST_URL ?>assets/img/logo-usdt.png" class="ct-image" loading="lazy"></a>
                    </i>
                </div>
            </div>

            <button id="payment-metamask-btn" class="ct-link-button button payment-btn btn-popup" type="submit" style="width: 100%; cursor: pointer;"><?php esc_html_e('Pay with metamask', 'woocommerce'); ?></button>
        </div>
    </div>
</div>