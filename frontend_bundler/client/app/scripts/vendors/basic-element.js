class BasicElement {

    /**
     * Constructor
     */
    constructor(args) {

        /**
         * Settings
         */
        this.settings = Object.assign({
            selector: null,
            debug: false
        }, args)

        /**
         * Vars
         */
        this.cs = this.getNodeList(this.settings.selector)
        this.csLength = this.cs.length
    }



    /**
     * 
     * @param {string} type Type of HTML element
     * @param {Array} classes Array classes to add to element
     * @param {string} text The innerText
     * @param {string} html The innerHTML
     * @param {object} insert Object with position and element where it should be inserted 
     * @returns elem
     */
    createDOMElement(type, classes, text, html, insert) {
        
        const elem = document.createElement(type)

        elem.setAttribute('class', classes.join(' '))

        if (text) {
            elem.innerText = text
        }

        if (html) {
            elem.innerHTML = html
        }

        if (insert) {
            insert.elem.insertAdjacentElement(insert.pos, elem)
        }

        return elem
    }



    /**
     * Get selected elements
     * 
     * @param {HTMLElement or string} element Selector element
     * @returns
     */
    getNodeList(element) {
        const nodeList = [];

        // The snippet is called on a single HTMLElement
        if (element && element instanceof HTMLElement && element.tagName.toUpperCase() === 'SELECT') {
            nodeList.push(element)

            // The snippet is called on a selector
        } else if (element && typeof element === 'string') {
            const elementsList = document.querySelectorAll(element);
            for (let i = 0, l = elementsList.length; i < l; ++i) {
                if (elementsList[i] instanceof HTMLElement
                    && elementsList[i].tagName.toUpperCase() === 'SELECT') {
                    nodeList.push(elementsList[i])
                }
            }

            // The snippet is called on any HTMLElements list (NodeList, HTMLCollection, Array, etc.)
        } else if (element && element.length) {
            for (let i = 0, l = element.length; i < l; ++i) {
                if (element[i] instanceof HTMLElement
                    && element[i].tagName.toUpperCase() === 'SELECT') {
                    nodeList.push(element[i])
                }
            }
        }

        return nodeList
    }
}

export default BasicElement
