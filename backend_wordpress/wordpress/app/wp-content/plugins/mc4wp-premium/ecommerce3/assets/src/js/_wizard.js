const m = require('mithril');

/**
 *
 * @param {HTMLElement} mount
 * @param {string} type
 * @param {array} ids
 * @returns {{view: (function(): *)}}
 */
function wizard(mount, type, ids) {
  let running = false;
  let current = 0;
  let progress = 0.00;
  let done = ids.length === 0;
  let log = [];
  const statusLabel = mount.parentElement.querySelector('.mc4wp-status-label');

  const tick = () => {
    m.request({
      method: 'POST',
      url: `${window.ajaxurl}?action=mc4wp_ecommerce_sync_${type}`,
      body: ids.slice(current, current + 10),
    }).then((results) => {
      current += results.length;
      progress = current / ids.length;

      // add results to log
      log.push({
        date: new Date(),
        messages: results,
      });

      // keep going or finish
      if (current < ids.length) {
        tick();
      } else {
        running = false;
        done = true;
      }
    });
  };

  const toggle = function () {
    statusLabel.parentNode.removeChild(statusLabel);
    running = !running;
    if (!running) {
      return;
    }

    tick();
  };

  const scrollToBottom = function (vnode) {
    vnode.dom.scrollTop = vnode.dom.scrollHeight;
  };

  /**
  * @param {Event} evt
  */
  const clearResults = function (evt) {
    evt.preventDefault();
    log = [];
  };

  return {
    view() {
      return m('div', [
        m('p', [
          m('button.button', { onclick: toggle, disabled: done }, done ? 'All done!' : running ? 'Stop' : 'Start'),
          ' ',
          (running ? m('span.description', `${(progress * 100).toFixed(2)}% — Please wait... This can take a while if you have many ${type}.`) : ''),
        ]),
        m('div.mc4wp-margin-m', { style: log.length > 0 ? 'display: block;' : 'display: none;' }, [
          m('div.results', { style: 'max-height: 240px; overflow-y: scroll;', oncreate: scrollToBottom, onupdate: scrollToBottom }, log.map((l) => m('div.mc4wp-margin-s', [
            m('div', [m('strong', l.date.toLocaleString())]),
            l.messages.map((msg) => m('div', msg)),
          ]))),
          m('p', [
            m('a', { href: '', onclick: clearResults }, 'Clear results'),
          ]),
        ]),
      ]);
    },
  };
}

[].forEach.call(document.querySelectorAll('[data-wizard]'), (mountElement) => {
  const type = mountElement.getAttribute('data-wizard');
  const objectIds = JSON.parse(mountElement.getAttribute('data-object-ids'));

  m.mount(mountElement, wizard(mountElement, type, objectIds));
});
