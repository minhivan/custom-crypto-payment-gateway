

var successCallback = function (data) {

	var checkout_form = $('form.woocommerce-checkout');

	// add a token to our hidden input field
	// console.log(data) to find the token
	checkout_form.find('#misha_token').val(data.token);

	// deactivate the tokenRequest function event
	checkout_form.off('checkout_place_order', tokenRequest);

	// submit the form now
	checkout_form.submit();

};

var errorCallback = function (data) {
	console.log(data);
};

var checking = function () {

	// here will be a payment gateway function that process all the card data from your form,
	// maybe it will need your Publishable API key which is misha_params.publishableKey
	// and fires successCallback() on success and errorCallback on failure
	console.log('Checkout submit!');
	var crypto_method = jQuery('form.woocommerce-checkout input[name="payment_method"]:checked').val();
	// if (crypto_method === 'crypto_pay') {
	// 	return false;
	// }
	return true;
};

jQuery(document).ready(function ($) {

	var checkout_form = $('form.woocommerce-checkout');

	checkout_form.on('checkout_place_order', checking);

});