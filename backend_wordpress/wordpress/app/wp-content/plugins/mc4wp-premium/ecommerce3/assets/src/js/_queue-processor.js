const m = require('mithril');

const { i18n } = window.mc4wp_ecommerce;
const state = {
  working: false,
  done: false,
  action: 'process',
};

/**
 * @param {Event} evt
 */
function handleProcessClick(evt) {
  state.action = evt.target.value;
}

/**
 * @param {Event} evt
 */
function handleResetClick(evt) {
  if (window.confirm(i18n.confirmation)) {
    state.action = evt.target.value;
  } else {
    evt.preventDefault();
  }
}

/**
 * @param {Event} evt
 */
function process(evt) {
  if (evt) evt.preventDefault();

  state.working = true;
  state.done = false;

  m.request({
    method: 'POST',
    url: `${window.ajaxurl}?action=mc4wp_ecommerce_${state.action}_queue`,
  }).then(() => {
    state.done = true;
    state.working = false;

    // update element stating number of pending background jobs
    document.getElementById('mc4wp-pending-background-jobs-count').innerText = '0';
  });
}

function view() {
  return m('form', {
    method: 'POST',
    onsubmit: process,
  }, [
    m('p', [
      m('button', {
        type: 'submit',
        className: 'button button-primary',
        value: 'process',
        disabled: state.working || state.done,
        onclick: handleProcessClick,
      }, state.done && state.action === 'process' ? i18n.done : i18n.process),
      m.trust('&nbsp; or &nbsp;'),
      m('button', {
        type: 'submit',
        className: 'button button-link-delete',
        value: 'reset',
        disabled: state.working || state.done,
        onclick: handleResetClick,
      }, state.done && state.action === 'reset' ? i18n.done : i18n.reset),
    ]),
    state.working ? m('p.description', [' ', m('span.mc4wp-loader'), ' ', m('span', i18n.processing)]) : '',
  ]);
}

module.exports = { view };
