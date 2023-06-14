/**
 * @param {Event} evt
 */
function askForConfirmation(evt) {
  const sure = window.confirm(evt.target.getAttribute('data-confirm') || window.mc4wp_ecommerce.i18n.confirmation);
  if (!sure) {
    evt.preventDefault();
  }
}

const confirmationElements = document.querySelectorAll('[data-confirm]');
for (let i = 0; i < confirmationElements.length; i++) {
  confirmationElements[i].addEventListener(confirmationElements[i].tagName === 'FORM' ? 'submit' : 'click', askForConfirmation);
}
