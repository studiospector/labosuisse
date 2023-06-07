const NAME_INDEX_REGEX = /\[(\d+)\]/;

/**
 * @param {HTMLElement} context
 * @constructor
 */
function FieldMapper(context) {
  function setAvailableFields() {
    const values = [].map.call(context.querySelectorAll('.mailchimp-field'), (el) => el.value);
    [].forEach.call(context.querySelectorAll('.mailchimp-field'), (el) => {
      const selected = el.value;
      [].forEach.call(el.querySelectorAll('option'), (opt) => {
        opt.disabled = opt.value === '' || (values.indexOf(opt.value) > -1 && selected !== opt.value);
      });
    });
  }

  function addRow() {
    const row = context.querySelector('.field-map-row');
    const newRow = row.cloneNode(true);
    const userField = newRow.querySelector('.user-field');
    const mailchimpField = newRow.querySelector('.mailchimp-field');
    const idx = Math.round(Math.random() * 10000);
    userField.value = '';
    userField.setAttribute('name', userField.name.replace(NAME_INDEX_REGEX, `[${idx}]`));
    mailchimpField.value = '';
    mailchimpField.setAttribute('name', mailchimpField.name.replace(NAME_INDEX_REGEX, `[${idx}]`));
    row.parentElement.appendChild(newRow);
    setAvailableFields();
  }

  /**
   * @param {Event} evt
   */
  function onClickHandler(evt) {
    if (evt.target.tagName === 'INPUT' && evt.target.className && evt.target.className.indexOf('remove-row') > -1) {
      evt.target.parentElement.parentNode.removeChild(evt.target.parentElement);
      setAvailableFields();
    }
  }

  /**
   * @param {Event} evt
   */
  function onChangeHandler(evt) {
    if (evt.target.tagName === 'SELECT' && evt.target.className.indexOf('mailchimp-field') > -1) {
      setAvailableFields();
    }
  }

  context.querySelector('.add-row').addEventListener('click', addRow);
  context.addEventListener('click', onClickHandler);
  context.addEventListener('change', onChangeHandler);

  setAvailableFields();
}

module.exports = FieldMapper;
