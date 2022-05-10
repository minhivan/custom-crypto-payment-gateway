jQuery(document).ready(function ($) {

    $(document).on("click","#transaction-submit",function(e) {
        e.preventDefault();
        alert("click bound to document listening for #test-element");
    });
});


var successCallback = function (data) {

	var checkout_form = jQuery('form.woocommerce-checkout');

	// add a token to our hidden input field


	// deactivate the tokenRequest function event
	checkout_form.off('checkout_place_order', tokenRequest);

	// submit the form now
	checkout_form.submit();

};

var errorCallback = function (data) {
	console.log(data);
};

var checking = function () {
	console.log('Checkout submit!');
	var crypto_method = jQuery('.woocommerce-checkout form#order_review input[name="payment_method"]:checked').val();
	if (crypto_method === 'crypto_pay') {
		return false;
	}
	return true;
};

jQuery(document).ready(function ($) {
	// var checkout_form = $('.woocommerce-checkout form#order_review');
	// checkout_form.on('checkout_place_order', checking);
    var checkout_form = $('form.checkout');

    checkout_form.on('checkout_place_order', function () {
        console.log("asdasdaddas");
        // if ($('#confirm-order-flag').length == 0) {
        //     checkout_form.append('<input type="hidden" id="confirm-order-flag" name="confirm-order-flag" value="1">');
        // }
        return true;
    });
});