const m = require('mithril');

const items = [];

/**
 * @param {string} msg
 */
function log(msg) {
  const line = {
    time: new Date(),
    text: msg,
  };

  items.push(line);
  m.redraw();
}

/**
 * @param {HTMLElement} element
 */
function scroll(element) {
  element.scrollTop = element.scrollHeight;
}

function view() {
  return m('div.log', { config: scroll }, [
    items.map((item) => {
      const timeString = `${(`0${item.time.getHours()}`).slice(-2)}:${
        (`0${item.time.getMinutes()}`).slice(-2)}:${
        (`0${item.time.getSeconds()}`).slice(-2)}`;

      return m('div', [
        m('span.time', timeString),
        m.trust(item.text),
      ]);
    }),
  ]);
}

module.exports = {
  log,
  view,
};
