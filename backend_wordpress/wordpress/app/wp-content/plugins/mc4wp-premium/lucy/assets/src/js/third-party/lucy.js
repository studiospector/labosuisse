const m = require('mithril');
const algoliasearch = require('algoliasearch/lite');

/**
 * @param {KeyboardEvent} event
 */
function listenForInput(event) {
  const { value } = event.target;

  // revert to list of links when search query empty
  if (value === '' && this.searchQuery !== '') {
    this.reset();
    return;
  }

  this.searchQuery = value;

  // perform search on [ENTER]
  if (event.keyCode === 13) {
    this.search(value);
  }
}

/**
 * Construct a new Lucy element
 *
 * @param {string} algoliaAppId
 * @param {string} algoliaAppKey
 * @param {string} algoliaIndexName
 * @param {object[]} links
 * @param {string} contactLink
 * @constructor
 */
function Lucy(algoliaAppId, algoliaAppKey, algoliaIndexName, links, contactLink) {
  this.algolia = algoliasearch(algoliaAppId, algoliaAppKey).initIndex(algoliaIndexName);
  this.opened = false;
  this.loader = null;
  this.searchResults = null;
  this.searchQuery = '';
  this.element = document.createElement('div');
  this.element.setAttribute('class', 'lucy closed');
  this.hrefLinks = links;
  this.hrefContactLink = contactLink;

  document.body.appendChild(this.element);
  m.mount(this.element, { view: this.getDOM.bind(this) });
}

Lucy.prototype.getDOM = function () {
  let results = '';

  if (this.searchQuery.length > 0) {
    if (this.searchResults === null) {
      results = m('em.search-pending', `Hit [ENTER] to search for "${this.searchQuery}"..`);
    } else if (this.searchResults.length > 0) {
      results = this.searchResults.map((l) => m('a', { href: l.href }, m.trust(l.text)));
    } else {
      results = m('em.search-pending', `Nothing found for "${this.searchQuery}"..`);
    }
  }

  return [
    m('div', { style: { display: this.opened ? 'block' : 'none' } }, [
      m('span.lucy-close-icon', { onclick: this.close.bind(this) }, ''),
      m('div.lucy-header', [
        m('h4', 'Looking for help?'),
        m('div.lucy-search-form', {
          onsubmit: this.search.bind(this),
        }, [
          m('input', {
            type: 'text',
            value: this.searchQuery,
            onkeyup: listenForInput.bind(this),
            onupdate: (function (vnode) { if (this.opened) { vnode.dom.focus(); } }).bind(this),
            placeholder: 'What are you looking for?',
          }),
          m('span.loader', {
            oncreate: (function (vnode) {
              this.loader = vnode.dom;
            }).bind(this),
          }),
          m('input', { type: 'submit' }),
        ]),
      ]),
      m('div.lucy-content', [

        m('div.lucy-links', { style: { display: this.searchQuery.length > 0 ? 'none' : 'block' } }, this.hrefLinks.map((l) => m('a', { href: l.href }, m.trust(l.text)))),

        m('div.lucy-search-results', results),

      ]),
      m('div.lucy-footer', [
        m('span', "Can't find the answer you're looking for?"),
        m('a', { class: 'button button-primary', href: this.hrefContactLink, target: '_blank' }, 'Contact Support'),
      ]),
    ]),
    m('span.lucy-button', {
      onclick: this.open.bind(this),
      style: { display: this.opened ? 'none' : 'block' },
    }, [
      m('span.lucy-button-text', 'Need help?'),
    ]),
  ];
};

/**
 * Opens (expands) the Lucy element
 */
Lucy.prototype.open = function () {
  if (this.opened) return;
  this.opened = true;

  this.element.setAttribute('class', 'lucy open');

  m.redraw();

  document.addEventListener('keyup', this.maybeClose.bind(this));
  document.addEventListener('click', this.maybeClose.bind(this));
};

/**
 * Closes the Lucy element
 */
Lucy.prototype.close = function () {
  if (!this.opened) return;
  this.opened = false;

  this.reset();
  this.element.setAttribute('class', 'lucy closed');

  document.removeEventListener('keyup', this.maybeClose.bind(this));
  document.removeEventListener('click', this.maybeClose.bind(this));
};

/**
 * @param {Event|KeyboardEvent} event
 */
Lucy.prototype.maybeClose = function (event) {
  // close when pressing ESCAPE
  if (event.type === 'keyup' && event.keyCode === 27) {
    this.close();
    return;
  }

  // close when clicking ANY element outside of Lucy
  if (event.type === 'click' && this.element.contains && !this.element.contains(event.target)) {
    this.close();
  }
};

Lucy.prototype.reset = function () {
  this.searchQuery = '';
  this.searchResults = null;
  m.redraw();
};

/**
 * @param {string} query
 */
Lucy.prototype.search = function (query) {
  const { loader } = this;
  const tick = function () {
    loader.innerText += '.';

    if (loader.innerText.length > 3) {
      loader.innerText = '.';
    }
  };

  // start loader
  loader.innerText = '.';
  const loadingInterval = window.setInterval(tick, 333);

  this.algolia.search(query, { hitsPerPage: 5 })
    .then((result) => {
      this.searchResults = [];

      /* clear loader */
      loader.innerText = '';
      window.clearInterval(loadingInterval);

      this.searchResults = result.hits.map((r) => ({
        href: r.url,

        // eslint-disable-next-line no-underscore-dangle
        text: r._highlightResult.title.value,
      }));

      m.redraw();
    });
};

module.exports = Lucy;
