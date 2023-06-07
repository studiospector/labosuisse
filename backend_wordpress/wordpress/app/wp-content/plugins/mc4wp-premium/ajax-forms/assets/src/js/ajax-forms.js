const Loader = require('./_form-loader.js');

const config = window.mc4wp_ajax_vars;
let busy = false;

/**
 * @param {HTMLFormElement} form
 * @param {object} response
 */
function handleResponseData(form, response) {
  const { forms } = window.mc4wp;

  forms.trigger('submitted', [form, null]);

  if (response.error) {
    form.setResponse(response.error.message);
    forms.trigger('error', [form, response.error.errors]);
  } else {
    const data = form.getData();

    // trigger events
    forms.trigger('success', [form, data]);
    forms.trigger(response.data.event, [form, data]);

    // for BC: always trigger "subscribed" event when firing "updated_subscriber" event
    // third boolean parameter indicates this was a BC event
    if (response.data.event === 'updated_subscriber') {
      forms.trigger('subscribed', [form, data, true]);
    }

    if (response.data.hide_fields) {
      form.element.querySelector('.mc4wp-form-fields').style.display = 'none';
    }

    // show success message
    form.setResponse(response.data.message);

    // reset form element
    form.element.reset();

    // maybe redirect to url
    if (response.data.redirect_to) {
      window.location.href = response.data.redirect_to;
    }
  }
}

/**
 * Submits the given form element over AJAX
 * @param {HTMLFormElement} form
 */
function submit(form) {
  // do nothing if still handling a previous submit
  if (busy) {
    return;
  }

  // Show loading indicator
  const loader = new Loader(form.element, config.loading_character);
  loader.start();

  // Clear possible errors from previous submit
  form.setResponse('');

  // set flag to prevent submitting twice
  busy = true;

  // issue an XmlHttpRequest
  const request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState >= XMLHttpRequest.DONE) {
      // remove loading indicator
      loader.stop();

      // clear flag that prevents submitting twice
      busy = false;

      if (request.status >= 200 && request.status < 400) {
        try {
          const data = JSON.parse(request.responseText);
          handleResponseData(form, data);
        } catch (e) {
          // eslint-disable-next-line no-console
          console.error(`Mailchimp for WordPress: failed to parse response: "${e}"`);
          form.setResponse(`<div class="mc4wp-alert mc4wp-error"><p>${config.error_text}</p></div>`);
        }
      } else {
        // eslint-disable-next-line no-console
        console.error(`MailChimp for WordPress: request error: "${request.responseText}"`);
      }
    }
  };
  request.open('POST', config.ajax_url, true);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.setRequestHeader('Accept', 'application/json');
  request.send(form.getSerializedData());
}

/**
 * @param {HTMLFormElement} form
 * @param {Event} evt
 */
function maybeSubmitOverAjax(form, evt) {
  // does this form have AJAX enabled?
  if (form.element.getAttribute('class').indexOf('mc4wp-ajax') < 0) {
    return;
  }

  // blur active input field
  if (document.activeElement && document.activeElement.tagName === 'INPUT') {
    document.activeElement.blur();
  }

  try {
    submit(form);
  } catch (e) {
    // eslint-disable-next-line no-console
    console.error(e);
    return;
  }

  // prevent normal submit (which reloads the entire page)
  evt.preventDefault();
}

// failsafe against including script twice
if (typeof (window.mc4wp) === 'undefined') {
  // eslint-disable-next-line no-console
  console.warn('Mailchimp for WordPress Premium: Unable to initialize AJAX forms feature because Mailchimp for WordPress core script is not propery loaded.');
} else if (!config.inited) {
  window.mc4wp.forms.on('submit', maybeSubmitOverAjax);
  config.inited = true;
}
