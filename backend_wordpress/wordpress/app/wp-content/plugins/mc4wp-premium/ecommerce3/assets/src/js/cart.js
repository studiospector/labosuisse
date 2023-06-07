const checkoutForm = document.querySelector('form.woocommerce-checkout, form[name="checkout"]');
let previousEmailAddress = '';
let previousDataString;
let formSubmitted = false;
const ajaxurl = typeof (window.mc4wp_ecommerce_cart) !== 'undefined' ? window.mc4wp_ecommerce_cart.ajax_url : window.woocommerce_params.ajax_url;

/**
 * Simple RegEx check to see if an email address is somewhat valid
 *
 * @param {string} emailAddress
 * @returns {boolean}
 */
function isEmailAddressValid(emailAddress) {
  const regex = /\S+@\S+\.\S+/;
  return typeof (emailAddress) === 'string' && emailAddress !== '' && regex.test(emailAddress);
}

/**
 * @param {string} fieldName
 * @returns {string}
 */
function getFieldValue(fieldName) {
  const field = checkoutForm.querySelector(`[name="${fieldName}"]`);
  return field ? field.value.trim() : '';
}

/**
 * Sends the (possibly partial) checkout form data to the server,
 * so it can send it to Mailchimp as abandoned cart data
 * @param {boolean} async Whether to wait for the request to finish
 */
function sendFormData(async) {
  const data = {
    previous_billing_email: previousEmailAddress,
    billing_email: getFieldValue('billing_email'),
  };

  // do nothing if email address does not look valid
  if (!isEmailAddressValid(data.billing_email)) {
    return;
  }

  // add remaining checkout form fields
  const fields = ['billing_first_name', 'billing_last_name', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_country'];
  fields.forEach((name) => {
    data[name] = getFieldValue(name);
  });

  // schedule cart update if data changed from previous time we sent it to server
  const dataString = JSON.stringify(data);
  if (dataString !== previousDataString) {
    const request = new XMLHttpRequest();
    request.open('POST', `${ajaxurl}?action=mc4wp_ecommerce_schedule_cart`, async);
    request.setRequestHeader('Content-Type', 'application/json');
    request.send(dataString);

    previousDataString = dataString;
    previousEmailAddress = data.billing_email;
  }
}

if (checkoutForm) {
  checkoutForm.addEventListener('change', () => sendFormData(true));
  checkoutForm.addEventListener('submit', () => { formSubmitted = true; });

  // always send before unloading window, but not if form was already submitted
  window.addEventListener('beforeunload', () => {
    if (!formSubmitted) {
      sendFormData(false);
    }
  });
}
