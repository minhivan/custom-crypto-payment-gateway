const ICON_FAILED = "/modules/invests/assets/img/connect-false.png";
const ICON_PROCESSING = "/modules/invests/assets/img/coin-transfer.png";

const STATUS_SUCCESS = "success";
const STATUS_FAILED = "error";



const paymentPopup = (data) => {
    return `<div class="mfp">
                <div class="mfp-bg mfp-ready close"></div>
                <div class="mfp-wrap popup-notify mfp-close-btn-in mfp-auto-cursor mfp-ready " tabindex="-1">
                    <div class="mfp-container mfp-s-ready mfp-inline-holder" >
                        <div class="mfp-content">
                            <div class="ct-div-block pop-up inline" data-status="${data.status}" data-method="${data.method}">
                                <div class="ct-div-block head">
                                    <p class="ct-text-block body__1">${data.title}</p>
                                </div>
                                <div class="ct-div-block content notice">
                                    <div class="form-animation">
                                        <div class="ct-div-block wrapper">
                                            <img alt="" src="${data.icon}" class="ct-image">
                                            <h3 class="ct-headline heading__3">${data.messageTitle}</h3>
                                            <p class="ct-text-block body__2">${data.content}</p>
                                        </div>
                                    </div>
                                    <div class="ct-div-block submit">
                                        <p class="form-row-submit">
                                            ${data.secondaryButton}
                                            ${data.primaryButton}
                                        </p>
                                    </div>
                                </div>
                                <button title="Close (Esc)" type="button" class="mfp-close close">×</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
};


const activatePaymentPopup = (obj) => {
    let data = {};

    data.confirmText = "Continue";
    data.cancelText = "Cancel";
    data.status     = obj.status || "success";
    data.type     = obj.type || "payment";

    if (obj.method === "metamask-required") {
        data.content = `Please install metamask to continue payment`;
        data.messageTitle = `Metamask required`;
        data.title = `Warning`
        data.secondaryButton = ``;
        data.primaryText = "Install metamask now"
        data.primaryButton = `<button class="ct-link-button button-1-filled btn-primary" data-action="install-metamask" target="_self">${data.primaryText}</button>`
    }
    else {
        if (obj.status === STATUS_SUCCESS) {
            data.icon = ICON_PROCESSING;
            data.content = `Please wait while we process your top up conversion.`;
            data.messageTitle = `Order confirmed`;
            data.title = `Confirm order`
            data.secondaryText = "Start investing";
            data.secondaryButton = `<button class="ct-link-button button-1-outline btn-secondary" data-action="investment" target="_self">${data.secondaryText}</button>`;
            data.primaryText = "View history"
            data.primaryButton = `<button class="ct-link-button button-1-filled btn-primary" data-action="history" target="_self">${data.primaryText}</button>`
        }

        else {
            data.icon = ICON_FAILED;
            data.content = `There was a problem processing this payment. Please double-check the submitted details and try again`;
            data.messageTitle = `Order failed`;
            data.title = `Confirm order`
            data.secondaryText = "Retry";
            data.secondaryButton = `<button class="ct-link-button button-1-outline btn-secondary" data-action="retry" target="_self">${data.secondaryText}</button>`;
            data.primaryText = "View history"
            data.primaryButton = `<button class="ct-link-button button-1-filled btn-primary" data-action="history" target="_self">${data.primaryText}</button>`
        }

    }



    jQuery("body").prepend(paymentPopup(data));
    jQuery("body").attr("style", "overflow: hidden;");
};
