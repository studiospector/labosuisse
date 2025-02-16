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
            this.currInputType = this.cs[i].getAttribute('type')

            // MAIN CONTAINER
            this.mainContainer = this.createDOMElement('LABEL', ['custom-field', 'custom-input', `custom-input--${this.currInputType}`], null, null, {pos: 'beforebegin', elem: this.cs[i]})
            this.mainContainer.setAttribute('for', this.cs[i].getAttribute('id'))

            // HIDE MAIN ELEMENTS
            // this.currInputElem.style.display = 'none'
            // this.mainContainer.style.display = 'none'

            // DISABLED <input>
            if (this.currInputElem.disabled) {
                this.mainContainer.classList.add('custom-input--disabled')
            }

            // VARIANT
            this.inputVariant = this.cs[i].getAttribute('data-variant')
            if (this.currInputType == 'number') {
                this.inputVariant = 'secondary'
            }
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
            if (this.currInputType == 'password') {
                this.addIconNext('eye-on')
                this.inputIconNext.addEventListener('click', this.showHidePassword)
            }

            // SEARCH
            if (this.currInputType == 'search') {
                this.mainContainer.classList.add('custom-input--icon-prev-hide')

                this.addIconPrev('close')

                if (this.inputIconPrev) {
                    this.showHideIcons(this.cs[i].value, 'prev')
                    this.cs[i].addEventListener('input', (ev) => this.showHideIcons(ev.target.value, 'prev'))
                    this.inputIconPrev.addEventListener('click', (ev) => {
                        ev.preventDefault()
                        this.currInputElem.value = null
                        this.onFocus(this.currInputElem.value)
                        this.mainContainer.classList.add('custom-input--icon-prev-hide')
                    })
                }

                // Add Icon next
                let buttonTypeNext = this.cs[i].getAttribute('data-button-type-next')
                let buttonVariantNext = this.cs[i].getAttribute('data-button-variant-next')
                buttonTypeNext = buttonTypeNext ? buttonTypeNext : 'button'
                buttonVariantNext = buttonVariantNext ? buttonVariantNext : null
                this.addIconNext('icon-search', buttonTypeNext, buttonVariantNext)
            }

            // NUMBER
            if (this.currInputType == 'number') {
                let check = null
                const min = Number(this.cs[i].min) >= 0 ? Number(this.cs[i].min) : 0
                const max = Number(this.cs[i].max) <= 0 ? 100 : Number(this.cs[i].max)
                const step = Number(this.cs[i].step) >= 1 ? Number(this.cs[i].step) : 1
                
                this.addIconPrev('minus', 'button')
                this.addIconNext('plus', 'button')

                this.inputInfo = this.createDOMElement('DIV', ['custom-input-info', 'custom-input-info--error'], null, null, {pos: 'beforeend', elem: this.mainContainer})
                this.inputInfo.style.display = 'none'

                if (this.cs[i].value < min) {
                    this.cs[i].value = min
                } else if (this.cs[i].value > max) {
                    this.cs[i].value = max
                }

                this.inputIconNext.addEventListener('click', (ev) => {
                    let newVal = null
                    let oldValue = parseFloat((this.cs[i].value || 0))

                    newVal = oldValue >= max ? oldValue : oldValue + step

                    this.cs[i].value = newVal

                    const inputChangeEvent = new Event('change')
                    this.currInputElem.dispatchEvent(inputChangeEvent)

                    if (this.settings.woocommerceQuantitySupport) {
                        jQuery(($) => {
                            $(this.cs[i]).trigger('change')
                        })
                    }
                })

                this.inputIconPrev.addEventListener('click', (ev) => {
                    let newVal = null
                    let oldValue = parseFloat(this.cs[i].value || 0)

                    newVal = oldValue <= min ? oldValue : oldValue - step

                    this.cs[i].value = newVal

                    const inputChangeEvent = new Event('change')
                    this.currInputElem.dispatchEvent(inputChangeEvent)

                    if (this.settings.woocommerceQuantitySupport) {
                        jQuery(($) => {
                            $(this.cs[i]).trigger('change')
                        })
                    }
                })

                this.cs[i].addEventListener('change', (ev) => {
                    let newVal = null
                    let oldValue = parseFloat(ev.target.value || 0)

                    if (oldValue >= max || oldValue <= min) {
                        if (check || oldValue > max) {
                            this.mainContainer.classList.add('custom-input--error')
                            this.inputInfo.innerText = `Min: ${min} / Max: ${max}`
                            this.inputInfo.style.display = 'block'
                        }
                        check = true
                    } else {
                        this.mainContainer.classList.remove('custom-input--error')
                        this.inputInfo.style.display = 'none'
                        check = null
                    }

                    newVal = oldValue >= max ? max : oldValue

                    this.cs[i].value = newVal
                })

            } else {
                // Set focus
                if (this.currInputType == 'date') {
                    this.onFocus()
                } else {
                    this.onFocus(this.currInputElem.value)
                }

                // Custom method to update focus state
                this.cs[i].updateFocus = (value) => {
                    this.onFocus(value)
                }

                // Custom method to update state
                this.cs[i].updateState = (state, loading = false) => {
                    this.updateState(state, loading)
                }

                // Events on <input> focus
                if (this.currInputType != 'date') {
                    this.cs[i].addEventListener('focus', () => this.onFocus())
                    this.cs[i].addEventListener('blur', (el) => this.onFocus(el.target.value.length))
                }
            }

            // HIDE MAIN ELEMENTS
            // this.currInputElem.style.display = 'block'
            // this.mainContainer.style.display = 'flex'
        }
    }


    /**
     * Update input state
     * 
     * @param {String} state 'disable' or 'active'
     */
    updateState = (state, loading) => {
        if (state === 'disable') {
            this.currInputElem.disabled = true
            this.mainContainer.classList.add('custom-input--disabled')
            if (loading) {
                this.toggleLoader(true)
            }
        } else if (state === 'active') {
            this.currInputElem.disabled = false
            this.mainContainer.classList.remove('custom-input--disabled')
            if (loading) {
                this.toggleLoader(false)
            }
        }
    }



    /**
     * Focus and Blur State animations
     * 
     * @param {String} value Current input value
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
    addIconNext = (iconName, buttonType = null, buttonVariant = null) => {
        const variant = buttonVariant ? ` button-${buttonVariant}` : ''
        const icon = `
            ${buttonType ? '<button type="'+ buttonType +'" class="button'+ variant +'">' : ''}
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
     addIconPrev = (iconName, buttonType = null, buttonVariant = null) => {
        const variant = buttonVariant ? ` button-${buttonVariant}` : ''
        const icon = `
            ${buttonType ? '<button type="'+ buttonType +'" class="button'+ variant +'">' : ''}
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
     * Show hide Icons
     */
    showHideIcons = (value, icon) => {
        if (value.length <= 0) {
            this.mainContainer.classList.add(`custom-input--icon-${icon}-hide`)
        } else {
            this.mainContainer.classList.remove(`custom-input--icon-${icon}-hide`)
        }
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



    /**
     * Show hide loader
     */
    toggleLoader = (show) => {
        if (show) {
            this.loader = this.createDOMElement('DIV', ['custom-input__loader'], null, `<span class="lb-loader-spinner"></span>`, {pos: 'beforeend', elem: this.mainContainer})
        } else {
            this.loader.remove()
        }
    }
}

export default CustomInput
