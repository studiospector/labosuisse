const m = require('mithril');
const Wizard = require('./admin/wizard.js');
const FieldMapper = require('./admin/field-mapper.js');

// init wizard
const wizardContainer = document.getElementById('wizard');
if (wizardContainer) {
  m.mount(wizardContainer, Wizard);
}

// eslint-disable-next-line no-new
new FieldMapper(document.querySelector('.mc4wp-sync-field-map'));
