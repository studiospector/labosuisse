const AccordionElement = require('./_accordion-element.js');

/**
 * @param {HTMLElement} element
 * @constructor
 */
function Accordion(element) {
  const accordions = [];

  // add class to container
  element.className += ' accordion-container';

  // find accordion blocks
  const accordionElements = element.children;

  // hide all content blocks
  for (let i = 0; i < accordionElements.length; i++) {
    // only act on direct <div> children
    if (accordionElements[i].tagName.toUpperCase() !== 'DIV') {
      continue;
    }

    // create new accordion and add to list of accordions
    accordions.push(new AccordionElement(accordionElements[i]));
  }

  // open first accordion
  accordions[0].open();
}

module.exports = Accordion;
