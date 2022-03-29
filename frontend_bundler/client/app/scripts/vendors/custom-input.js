import BasicElement from './basic-element'

class CustomInput extends BasicElement {

    /**
     * Constructor
     */
    constructor(args) {

        super(args)

        // Vars
        this.currInputElem = null
        this.currInputElemLength = null
        this.currInputType = null
        this.mainContainer = null
        this.inputPlaceholder = null
        this.inputVariant = null
        this.inputIconNext = null

        // Init
        this.init()
    }



    /**
     * Init
     */
    init() {
        // Loop through queried <input>
        for (let i = 0; i < this.csLength; i++) {
            // Current <input>
            this.currInputElem = this.cs[i]
            this.currInputElemLength = this.currInputElem.length

            // MAIN CONTAINER
            this.mainContainer = this.createDOMElement('LABEL', ['custom-field', 'custom-input'], null, null, {pos: 'beforebegin', elem: this.cs[i]})
            this.mainContainer.setAttribute('for', this.cs[i].getAttribute('id'))

            // DISABLED <input>
            if (this.currInputElem.disabled) {
                this.mainContainer.classList.add('custom-input--disabled')
            }

            // VARIANT
            this.inputVariant = this.cs[i].getAttribute('data-variant')
            if (this.inputVariant) {
                this.mainContainer.classList.add(`custom-input--${this.inputVariant}`);
            }

            // PLACEHOLDER
            const inputPlaceholderText = this.cs[i].getAttribute('placeholder')
            if (inputPlaceholderText) {
                this.inputPlaceholder = this.createDOMElement('DIV', ['custom-input-label'], inputPlaceholderText, null, {pos: 'beforeend', elem: this.mainContainer})
            }

            // Move <input> into main container
            this.mainContainer.appendChild(this.cs[i])

            // PASSWORD
            this.currInputType = this.cs[i].getAttribute('type')
            if (this.currInputType == 'password') {
                this.addIconNext('eye-on')
                this.inputIconNext.addEventListener('click', this.showHidePassword)
            }

            // SEARCH
            this.currInputType = this.cs[i].getAttribute('type')
            if (this.currInputType == 'search') {
                if (this.inputVariant == 'primary' || this.inputVariant == 'tertiary') {
                    this.addIconPrev('close')

                    if (this.inputIconPrev) {
                        this.inputIconPrev.addEventListener('click', (ev) => {
                            ev.preventDefault()
                            this.currInputElem.value = null
                            this.onFocus(this.currInputElem.value)
                        })
                    }
                }

                // Add Icon next
                const buttonTypeNext = this.cs[i].getAttribute('data-button-type-next')
                this.addIconNext('icon-search', buttonTypeNext)
            }

            // Set default value
            this.onFocus(this.currInputElem.value)

            // Custom method to update state
            this.cs[i].updateFocus = (value) => {
                this.onFocus(value)
            }

            // Events on <input> focus
            this.cs[i].addEventListener('focus', () => this.onFocus())
            this.cs[i].addEventListener('blur', (el) => this.onFocus(el.target.value.length))
        }
    }



    /**
     * Focus and Blur State animations
     */
    onFocus = (value) => {
        this.mainContainer.classList.add('is-focus')

        if (value !== undefined && value == 0) {
            this.mainContainer.classList.remove('is-focus')
        }
    }



    /**
     * Add icon next to input
     * 
     * @param {string} iconName Icon name to add
     */
    addIconNext = (iconName, buttonType = null) => {
        const icon = `
            ${buttonType ? '<button type="'+ buttonType +'" class="button button-primary">' : ''}
            <span class="lb-icon">
                <svg aria-label="${iconName}" xmlns="http://www.w3.org/2000/svg">
                    <use xlink:href="#${iconName}"></use>
                </svg>
            </span>
            ${buttonType ? '</button>' : ''}
        `
        this.inputIconNext = this.createDOMElement('DIV', ['custom-input__icon', 'custom-input__icon--next'], null, icon, {pos: 'beforeend', elem: this.mainContainer})
        this.mainContainer.classList.add('custom-input--icon-next')
    }



    /**
     * Add icon prev to input
     * 
     * @param {string} iconName Icon name to add
     */
     addIconPrev = (iconName, buttonType = null) => {
        const icon = `
            ${buttonType ? '<button type="'+ buttonType +'" class="button button-primary">' : ''}
            <span class="lb-icon">
                <svg aria-label="${iconName}" xmlns="http://www.w3.org/2000/svg">
                    <use xlink:href="#${iconName}"></use>
                </svg>
            </span>
            ${buttonType ? '</button>' : ''}
        `
        this.inputIconPrev = this.createDOMElement('DIV', ['custom-input__icon', 'custom-input__icon--prev'], null, icon, {pos: 'beforeend', elem: this.mainContainer})
        this.mainContainer.classList.add('custom-input--icon-prev')
    }



    /**
     * Show hide password
     */
    showHidePassword = () => {
        const iconSVG = this.inputIconNext.querySelector('.lb-icon svg')
        const iconXLink = this.inputIconNext.querySelector('.lb-icon svg use')
        
        if (this.currInputElem.type == 'password') {
            this.currInputElem.type = 'text'
            iconSVG.setAttribute('aria-label', 'eye-off')
            iconXLink.setAttribute('xlink:href', '#eye-off')
        } else if (this.currInputElem.type == 'text') {
            this.currInputElem.type = 'password'
            iconSVG.setAttribute('aria-label', 'eye-on')
            iconXLink.setAttribute('xlink:href', '#eye-on')
        }
    }
}

export default CustomInput
