const mount = document.getElementById('email-message-template-tags');

/**
 * @param {function} callback
 * @param {int} delay
 */
function debounce(callback, delay) {
  let timeout;

  return () => {
    if (timeout) clearTimeout(timeout);
    timeout = window.setTimeout(callback, delay);
  };
}

function updateAvailableEmailTags() {
  const fields = window.mc4wp.forms.editor.query('[name]');
  const tags = ['_ALL_'];

  for (let i = 0; i < fields.length; i++) {
    let tagName = fields[i].getAttribute('name').toUpperCase();

    // strip empty arrays []
    // add in semicolon for named array keys
    tagName = tagName
      .replace('[]', '')
      .replace(/\[(\w+)\]/, ':$1');

    if (tags.indexOf(tagName) < 0) {
      tags.push(tagName);
    }
  }

  mount.innerHTML = tags.map((tagName) => `<input readonly style="background: transparent; border: 0; color: #222;" onclick="this.select();" onfocus="this.select()" value="[${tagName}]" />`).join(' ');
}

window.addEventListener('load', () => {
  window.mc4wp.forms.editor.on('change', debounce(updateAvailableEmailTags, 1000));
  updateAvailableEmailTags();
});
