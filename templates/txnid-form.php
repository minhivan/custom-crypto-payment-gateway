<div class="ct-div-block txn"> 
    <div class="ct-div-block info-qr">
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
                        <img alt="" src="/modules/invests/assets/img/logo-binance.png" class="ct-image" loading="lazy"></a>
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
                    <input type="text" class="input-text form-control" name="total" id="total" value="1000 USDT" placeholder="<?php esc_html_e('Total', 'woocommerce'); ?>" disabled>
                    <i class="ic">
                        <img alt="" src="/modules/invests/assets/img/logo-usdt.png" class="ct-image" loading="lazy"></a>
                    </i>
                </p>
            </div>
        </div>
        <div class="ct-div-block qr">
            <img alt="" src="/modules/invests/assets/img/qr.png" class="ct-image" loading="lazy">
            <p class="ct-div-block body__2">
                <?php esc_html_e('Scan this QR Code in your wallet app to do the payment', 'woocommerce'); ?></p>
        </div>
    </div>
    <div class="ct-div-block step">
        <p class="ct-div-block body__1"><?php esc_html_e('Payment Instruction', 'woocommerce'); ?></p>
        <div class="ct-div-block step-wrap">
            <div class="ct-div-block item">
                <div class="ct-dv-block line"></div>
                <lord-icon target="div" src="/modules/invests/assets/lordicon/1686-scan-qr-code-outline-edited.json" trigger="hover" style="width:64px;height:64px"></lord-icon>
                <p class="ct-div-block heading__5"><?php esc_html_e('Step 1', 'woocommerce'); ?></p>
                <p class="ct-div-block body__3">
                    <?php esc_html_e('Scan the QR code in your wallet application', 'woocommerce'); ?></p>
            </div>
            <div class="ct-div-block item">
                <div class="ct-dv-block line"></div>
                <lord-icon target="div" src="/modules/invests/assets/lordicon/964-omnichannel-outline-edited.json" trigger="hover" style="width:64px;height:64px"></lord-icon>
                <p class="ct-div-block heading__5"><?php esc_html_e('Step 2', 'woocommerce'); ?></p>
                <p class="ct-div-block body__3">
                    <?php esc_html_e('Enter the Network and Crypto Currency in your wallet application.', 'woocommerce'); ?>
                </p>
            </div>
            <div class="ct-div-block item">
                <div class="ct-dv-block line"></div>
                <lord-icon target="div" src="/modules/invests/assets/lordicon/245-edit-document-outline-edited.json" trigger="hover" style="width:64px;height:64px"></lord-icon>
                <p class="ct-div-block heading__5"><?php esc_html_e('Step 2', 'woocommerce'); ?></p>
                <p class="ct-div-block body__3">
                    <?php esc_html_e('Fill in theTXN ID below to confirm the transaction.', 'woocommerce'); ?></p>
            </div>
        </div>
        <div class="form-animation">
            <div class="ct-div-block form-item">
                <p class="form-row my-field-class form-row-wide validate-required" id="txn_id_field" data-priority="">
                    <label for="txn_id" class=""><?php esc_html_e('TXN ID', 'woocommerce'); ?></label>
                    <input type="text" class="input-text" name="txn_id" id="txn_id" placeholder="<?php esc_html_e('TXN ID', 'woocommerce'); ?>" required>
                    <input id="wallet_address" type="hidden" value="<?php echo  isset($_GET['wallet']) ? $_GET['wallet']  : ""; ?>" require />

                    <i class="ic">
                        <lord-icon target="p" src="/modules/invests/assets/lordicon/245-edit-document-outline-edited.json" trigger="click" style="width:20px;height:20px"></lord-icon>
                    </i>
                    <span class="ct-div-block caption__2 error-mess">
                        <?php esc_html_e('Please fill in TXD ID to confirm the transaction.', 'woocommerce'); ?></span>
                </p>
            </div>
        </div>
    </div>
    <span id="transaction-submit" class="ct-link-button button" type="submit" style="width: 100%;" data-id="<?= $order_id  ?>"><?php esc_html_e('Submit', 'woocommerce'); ?></span>
</div>