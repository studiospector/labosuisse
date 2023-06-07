/**
 * @type {string[]}
 */
const suggestions = window.mc4wp_autocomplete_vars.domains;

/**
 * Set up a new Autocompleter instance
 *
 * @param {HTMLInputElement} inputEl
 * @constructor
 */
function Autocompleter(inputEl) {
  this.input = inputEl;
  this.focus = -1;
  this.autocompleteEl = null;
  inputEl.addEventListener('input', this.onInput.bind(this));
  inputEl.addEventListener('keydown', this.onKeyDown.bind(this));

  // clicking anywhere closes the suggestions box
  // this click could be on a suggestion, in
  document.addEventListener('click', this.close.bind(this));
}

/**
 * Removes the list of autocomplete suggestions from the DOM
 */
Autocompleter.prototype.close = function () {
  if (!this.autocompleteEl) return;
  this.autocompleteEl.parentNode.removeChild(this.autocompleteEl);
  this.autocompleteEl = null;
};

/**
 * Handler for `input.input` events
 * Creates a new list of suggestions and adds it to the DOM.
 */
Autocompleter.prototype.onInput = function () {
  const { input } = this;
  const { value } = input;
  const atPos = value.indexOf('@');
  if (atPos <= 0) {
    return;
  }

  const emailPart = value.substring(0, atPos);
  const domainPrefix = value.substring(atPos + 1);
  if (!domainPrefix) {
    return;
  }

  // remove previous list of suggestions
  this.close();

  // create HTML for new list of suggestions
  const clickEventHandler = this.onSuggestionClick.bind(this);
  const autocompleteList = document.createElement('div');
  autocompleteList.setAttribute('class', 'mc4wp-autocomplete-items');
  autocompleteList.style.width = `${input.offsetWidth}px`;
  autocompleteList.style.left = `${input.offsetLeft}px`;
  suggestions.forEach((suggestion) => {
    // only add suggestion to list if prefix matches
    if (suggestion.substring(0, domainPrefix.length).toUpperCase() !== domainPrefix.toUpperCase()) {
      return;
    }

    const el = document.createElement('div');
    el.setAttribute('data-value', `${emailPart}@${suggestion}`);
    el.appendChild(document.createTextNode(`${emailPart}@`));
    const matchedDomainEl = document.createElement('strong');
    matchedDomainEl.textContent = suggestion.substring(0, domainPrefix.length);
    el.appendChild(matchedDomainEl);
    el.appendChild(document.createTextNode(suggestion.substring(domainPrefix.length)));
    el.addEventListener('click', clickEventHandler);
    autocompleteList.appendChild(el);
  });

  // set HTML for list of suggestions & add to DOM
  this.input.parentNode.appendChild(autocompleteList);
  this.focus = -1;
  this.autocompleteEl = autocompleteList;
};

/**
 * @param {MouseEvent} evt
 */
Autocompleter.prototype.onSuggestionClick = function (evt) {
  this.input.value = evt.target.getAttribute('data-value');
};

/**
 * Handler for `input.keydown` events
 *
 * @param {KeyboardEvent} evt
 */
Autocompleter.prototype.onKeyDown = function (evt) {
  if (!this.autocompleteEl) {
    return;
  }
  switch (evt.code) {
    case 'ArrowDown': {
      this.focus++;
      evt.preventDefault();
      break;
    }
    case 'ArrowUp': {
      this.focus--;
      evt.preventDefault();
      break;
    }
    case 'Tab':
    case 'Escape':
      // Close the list of suggestions when ESCAPE or TAB key was pressed in this input's context
      this.close();
      return;
    case 'Enter': {
      // if RETURN was pressed and a suggestion has focus
      // simulate a click event on it to copy it to the input field
      if (this.focus > -1) {
        evt.preventDefault();
        this.autocompleteEl.children[this.focus].click();
        return;
      }
      break;
    }
    default: break;
  }

  this.focus = Math.max(0, this.focus);
  this.focus = Math.min(this.autocompleteEl.children.length - 1, this.focus);
  this.addClassToFocused();
};

/**
 * Adds an HTML class attribute to the focused suggestion
 */
Autocompleter.prototype.addClassToFocused = function () {
  for (let i = 0; i < this.autocompleteEl.children.length; i++) {
    this.autocompleteEl.children[i].classList.toggle('mc4wp-autocomplete-active', i === this.focus);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  [].forEach.call(document.querySelectorAll('.mc4wp-form.autocomplete input[type="email"]'), (el) => new Autocompleter(el));
});
