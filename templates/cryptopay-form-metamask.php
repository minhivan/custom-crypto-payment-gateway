<div class="ct-div-block txn">
    <div class="ct-div-block info-qr" style="width: 100%;">
        <p class="ct-div-block body__1"><?php esc_html_e('Zomland information', 'woocommerce'); ?></p>
        <div class="ct-div-block info">
            <div class="form-animation">
                <p class="form-row form-row-first form-group validate-required other" id="address_field" data-priority="10">
                    <span class="label"><?php esc_html_e('Zomland Wallet Address', 'woocommerce'); ?></span>
                    <span class="text key-code">1235gxvdghshhgjhhh7686h36bvhs</span>
                    <span class="copy key-copy-btn">
                        <img alt="" src="/modules/invests/assets/icons/copy-primary.svg" class="ct-image" loading="lazy">
                    </span>
                    <span class="success-key-copy" style="display: none;"><?php esc_attr_e('Copied to clipboard', 'woocommerce'); ?></span>
                    <i class="ic">
                        <lord-icon target="p" src="/modules/invests/assets/lordicon/179-email-book-outline-edited.json" trigger="click" style="width:20px;height:20px"></lord-icon>
                    </i>
                </p>
                <p class="form-row form-row-first form-group validate-required has-value" id="network_field" data-priority="10">
                    <label for="network" class=""><?php esc_html_e('Network', 'woocommerce'); ?></label>
                    <input type="text" class="input-text form-control" name="network" id="network" value="BSC (BEP20)" placeholder="<?php esc_html_e('Network', 'woocommerce'); ?>" disabled>
                    <i class="ic">
                        <img alt="" src="/modules/invests/assets/img/logo-binance.png" class="ct-image" loading="lazy">
                    </i>
                </p>
                <p class="form-row form-row-first form-group validate-required has-value" id="currency_field" data-priority="10">
                    <label for="currency" class=""><?php esc_html_e('Crypto currency', 'woocommerce'); ?></label>
                    <input type="text" class="input-text form-control" name="currency" id="currency" value="USDT" placeholder="<?php esc_html_e('Crypto currency', 'woocommerce'); ?>" disabled>
                    <i class="ic">
                        <img alt="" src="/modules/invests/assets/img/logo-usdt.png" class="ct-image" loading="lazy"></a>
                    </i>
                </p>
                <p class="form-row form-row-first form-group validate-required has-value" id="total_field" data-priority="10">
                    <label for="total" class=""><?php esc_html_e('Total', 'woocommerce'); ?></label>
                    <input type="text" class="input-text form-control" name="total" id="total" value="- USDT" placeholder="<?php esc_html_e('Total', 'woocommerce'); ?>" disabled>
                    <i class="ic">
                        <img alt="" src="/modules/invests/assets/img/logo-usdt.png" class="ct-image" loading="lazy"></a>
                    </i>
                </p>
            </div>
        </div>
    </div>

    <span id="transaction-submit" class="ct-link-button button" type="submit" style="width: 100%;"><?php esc_html_e('Submit', 'woocommerce'); ?></span>
</div>