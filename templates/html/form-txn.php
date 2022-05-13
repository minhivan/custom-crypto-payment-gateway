<div class="ct-div-block txn">
    <div class="ct-div-block info-qr">
        <p class="ct-div-block body__1"><?php esc_html_e('Zomland information', 'woocommerce'); ?></p>
        <div class="ct-div-block info">
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
                        <img alt="" src="<?php echo INVEST_URL ?>assets/img/logo-binance.png" class="ct-image" loading="lazy"></a>
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
        </div>
        <div class="ct-div-block qr">
            <img alt="" src="<?php echo INVEST_URL ?>assets/img/qr.png" class="ct-image" loading="lazy">
            <p class="ct-div-block body__2">
                <?php esc_html_e('Scan this QR Code in your wallet app to do the payment', 'woocommerce'); ?></p>
        </div>
    </div>
    <div class="ct-div-block step">
        <p class="ct-div-block body__1"><?php esc_html_e('Payment Instruction', 'woocommerce'); ?></p>
        <div class="ct-div-block step-wrap">
            <div class="ct-div-block item">
                <div class="ct-dv-block line"></div>
                <lord-icon target="div" src="<?php echo INVEST_URL ?>assets/lordicon/1686-scan-qr-code-outline-edited.json" trigger="hover" style="width:64px;height:64px"></lord-icon>
                <p class="ct-div-block heading__5"><?php esc_html_e('Step 1', 'woocommerce'); ?></p>
                <p class="ct-div-block body__3">
                    <?php esc_html_e('Scan the QR code in your wallet application', 'woocommerce'); ?></p>
            </div>
            <div class="ct-div-block item">
                <div class="ct-dv-block line"></div>
                <lord-icon target="div" src="<?php echo INVEST_URL ?>assets/lordicon/964-omnichannel-outline-edited.json" trigger="hover" style="width:64px;height:64px"></lord-icon>
                <p class="ct-div-block heading__5"><?php esc_html_e('Step 2', 'woocommerce'); ?></p>
                <p class="ct-div-block body__3">
                    <?php esc_html_e('Enter the Network and Crypto Currency in your wallet application.', 'woocommerce'); ?>
                </p>
            </div>
            <div class="ct-div-block item">
                <div class="ct-dv-block line"></div>
                <lord-icon target="div" src="<?php echo INVEST_URL ?>assets/lordicon/245-edit-document-outline-edited.json" trigger="hover" style="width:64px;height:64px"></lord-icon>
                <p class="ct-div-block heading__5"><?php esc_html_e('Step 2', 'woocommerce'); ?></p>
                <p class="ct-div-block body__3">
                    <?php esc_html_e('Fill in theTXN ID below to confirm the transaction.', 'woocommerce'); ?></p>
            </div>
        </div>
        <div class="form-animation">

            <div class="form-row validate-required valid" id="txn_id_field" data-priority="">
                <input type="text" name="txn_id" id="txn_id" placeholder="<?php esc_html_e('TXN ID', 'woocommerce'); ?>" require />
                <label for="txn_id"><?php esc_html_e('TXN ID', 'woocommerce'); ?></label>
                <div class="line"></div>
                <i class="ic">
                    <lord-icon target="p" src="<?php echo INVEST_URL ?>assets/lordicon/245-edit-document-outline-edited.json" trigger="click" style="width:20px;height:20px"></lord-icon>
                </i>
                <span class="valid error-mess"><?php esc_html_e('Your number invalid', 'woocommerce'); ?></span>
            </div>

        </div>
    </div>
    <button id="transaction-submit" disabled class="ct-link-button button payment-btn" type="submit" style="width: 100%; cursor: pointer;" data-id="<?= $order_id  ?>"><?php esc_html_e('Pay now', 'woocommerce'); ?></button>
</div>