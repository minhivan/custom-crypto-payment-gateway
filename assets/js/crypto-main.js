
const USDT_ABI = JSON.parse(extraCryptoPay.master_abi);
const USDT_CONTRACT_ADDRESS = extraCryptoPay.contract_address;


var checking = function () {
    var crypto_method = jQuery('form.woocommerce-checkout.topup input[name="payment_method"]:checked').val();
    if (crypto_method !== 'crypto_pay') {
        return false;
    }
    return true;
};


// checking when placing order
jQuery(document).ready(function ($) {
    var checkout_form = $('form.woocommerce-checkout.topup');
    checkout_form.on('checkout_place_order', checking);
});

jQuery(document).ready(function ($) {
    var $form = $("form#order_review");

    $("#txn_id").on("keyup", function () {
        if ($(this).val().length === 66) {
            $("#transaction-submit").prop("disabled", false);
        }

        else {
            $(this).focus();
            jQuery(".caption__2").css("color", "red");
            $("#transaction-submit").prop("disabled", true);
        }
    })

    
    $(document).on("click", "#transaction-submit", async function (e) {
        e.preventDefault();
        let domHash = $("#txn_id"),
            hash = domHash.val() !== "" ? domHash.val() : false,
            popup = jQuery(".success-otp");

        $(this).prop("disabled", true);
        $form.addClass('processing').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        if (!hash || hash.length !== 66) {
            $(this).focus();
            jQuery(".caption__2").css("color", "red");
            return;
        }

        let obj = { status: "error", type: "payment", method: "manual" };

        let orderId = $("#order_review").data("id") || $("#transaction-submit").data("id");
        let data = {
            action: "crypto_pay_action_process",
            hash: hash,
            order_id: orderId,
            nonce: extraCryptoPay.nonce,
        };

        let result = await doAjax(data);

        if (result) {
            obj.status = "success";
        }

        $form.attr("data-status", obj.status)
        activatePaymentPopup(obj)

    });

    $(document).on("click", "#payment-metamask-btn", async function (e) {
        e.preventDefault();
        await onChangeMetamask();
        $(this).prop("disabled", true);
        $form.addClass('processing').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        let obj = { status: "error", type: "payment", method: "auto" }
        if (!window.isMetaMaskInstalled()) {
            obj.status = "error";
            obj.method = "metamask-required";
        }
        
        else {
            let paymentResult = await crypto_pay_metamask(extraCryptoPay);
            if (typeof paymentResult === 'object') {
                obj.status = "success";
            } 
        }

        $form.attr("data-status", obj.status)
        activatePaymentPopup(obj);
        
    });


    // click out off popup
    $(document).mouseup(function (e) {
        var container = $("div.mfp-content .pop-up");
        // if the target of the click isn't the container nor a descendant of the container
        if (container.length && !container.is(e.target) && container.has(e.target).length === 0) {
            if (container.attr("data-status") === "error") {
                $form.removeClass('processing').unblock();
                $("button.payment-btn").prop("disabled", false);
                return;
            }
            else {
                location.href = `/history`;
                return;
            }
        }
    });

    $(document).on("click", ".form-row-submit button", async function (e) {
        var container = $("div.mfp-content .pop-up");
        if (container.length) {
            let action = $(this).attr("data-action");
            console.log(action);
            if (action === "retry") {
                removePopup(); // from main js
                $form.removeClass('processing').unblock();
                $("button.payment-btn").prop("disabled", false);
                return;
            }
            if (action === "install-metamask") {
                location.href = `https://metamask.io/download.html`;
                removePopup();
                return;
            }
            location.href = `/${action}`;
            removePopup();
        }
    });

    $(document).on('click', '.mfp-close', function (e) {
        e.preventDefault();
        console.log($form.attr("data-status"));
        if ($form.attr("data-status") === "error") {
            $form.removeClass('processing').unblock();
            $("button.payment-btn").prop("disabled", false);
            return;
        }
        else {
            location.href = `/history`;
            return;
        }
    })
});

async function crypto_pay_metamask(data) {
    try {
        if (!window.clientCurrentAddress) {
            await window.ethereum.request({ method: 'eth_requestAccounts' }).then((accounts) => {
                window.handleChangeMetamaskAccount(accounts);
            })
        }
        if (!window.clientCurrentNetwork || window.clientCurrentNetwork !== 97) {
            await ethereum.request({
                method: 'wallet_switchEthereumChain',
                params: [{ chainId: '0x61' }],
            });
        }

        if (data.amount && data.user_wallet && data.receiving_wallet) {
            let amount = parseFloat(data.amount) / 1000;
            const usdt = new _web3.eth.Contract(USDT_ABI, USDT_CONTRACT_ADDRESS, {
                from: window.clientCurrentAddress // default from address
            });
            const count = await _web3.eth.getTransactionCount(window.clientCurrentAddress);

            const nonce = _web3.utils.toHex(count);
            const gasPrice = await _web3.eth.getGasPrice();
            const value = _web3.utils.toHex(0);

            // estimate gas
            const gasLimit = await usdt.methods.transfer(data.receiving_wallet, _web3.utils.toWei(amount.toString(), 'ether')).estimateGas({
                from: window.clientCurrentAddress
            });


            let rawTx = {
                from: window.clientCurrentAddress,
                nonce: nonce,
                value: value,
                gasPrice: _web3.utils.toHex(gasPrice),
                gasLimit: _web3.utils.toHex(gasLimit),
                to: USDT_CONTRACT_ADDRESS,
                data: usdt.methods.transfer(extraCryptoPay.receiving_wallet, _web3.utils.toWei(amount.toString(), 'ether')).encodeABI()
            }

            const txHash = await web3.eth.sendTransaction(rawTx)
                .on('transactionHash', async function (hash) {
                    let data = {
                        action: "crypto_pay_action_process",
                        hash: hash,
                        user_wallet: window.clientCurrentAddress,
                        order_id: extraCryptoPay.order_id,
                        nonce: extraCryptoPay.nonce,
                    };

                    let ajaxData = await doAjax(data);
                    console.log(ajaxData);
                    return hash;
                })
                .on('error', console.error);

            return {
                txHash,
                wallet: window.clientCurrentAddress
            };
        }
    } catch (error) {
        console.error;
        return false;
    }
}

async function doAjax(args) {
    let result;

    try {
        result = await jQuery.ajax({
            url:  extraCryptoPay.ajaxurl,
            type: 'POST',
            data: args
        });

    } catch (error) {
        console.error(error);
        return false;
    }
    return result;
}

const onChangeMetamask = async () => {
    // init local variable
    window.ethereum.on("networkChanged", async (networkId) => {
        console.log("Network change - ", networkId);
        window.clientCurrentNetwork = await web3.eth.net.getId();
        if (!window.clientCurrentAddress) {
            await getLoggedAccount();
        }
        await new Promise(resolve => setTimeout(resolve, 1000));
    });

    window.ethereum.on("accountsChanged", async (accounts) => {
        if (accounts.length === 0) {
            window.clientCurrentAddress     = null;
            window.clientCurrentNetwork     = null;
            window.clientCurrentNetworkType = null;
        } 
        else {
            window.clientCurrentNetwork = accounts[0];
        }
        await new Promise(resolve => setTimeout(resolve, 1000));
    });
}