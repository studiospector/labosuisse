import Component from '@okiba/component'
import { on } from '@okiba/dom'

const ui = {
    image: '.banner-alternate__img',
    infoboxText: '.js-infobox-text',
    infoboxCTA: '.infobox__cta',
    infoboxParagraph: '.infobox__paragraph',
}

class BannerAlternate extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.variant = this.el.getAttribute('data-variant')

        // Adjust images on resize for only mobile
        if (this.variant == 'infobox-right' && !this.el.classList.contains('banner-alternate--infobox-centered')) {
            setTimeout(() => {
                this.adjustImageOffsetDispatcher()
            }, 100);
            on(window, 'resize', this.adjustImageOffsetDispatcher)
        }
    }

    adjustImageOffsetDispatcher = () => {
        let matchMedia = window.matchMedia("screen and (max-width: 768px)")
        this.adjustImageOffset(matchMedia)
    }

    adjustImageOffset = (e) => {
        if (e.matches) {
            let textWrapHeight = 0, ctaWrapHeight = 0

            if (this.ui.infoboxParagraph && this.ui.infoboxCTA) {
                this.ui.infoboxCTA.insertAdjacentElement('afterbegin', this.ui.infoboxParagraph)
            }

            if (this.ui.infoboxText) {
                let textWrapHeight = this.getFullHeight(this.ui.infoboxText) + 40
                this.ui.image.style.marginTop = `${textWrapHeight}px`
            }

            if (this.ui.infoboxCTA) {
                let ctaWrapHeight = this.getFullHeight(this.ui.infoboxCTA) + 20
                this.ui.image.style.marginBottom = `${ctaWrapHeight}px`
            }

            if (textWrapHeight && ctaWrapHeight) {
                this.ui.image.style.height = `calc(100% - ${textWrapHeight + ctaWrapHeight}px)`
            }
        } else {
            if (this.ui.infoboxParagraph && this.ui.infoboxText) {
                this.ui.infoboxText.insertAdjacentElement('afterend', this.ui.infoboxParagraph)
            }
            this.ui.image.style.marginTop = `0px`
            this.ui.image.style.marginBottom = `0px`
            this.ui.image.style.height = `100%`
        }
    }

    getFullHeight(el) {
        let elHeight = el.offsetHeight

        elHeight += parseInt(window.getComputedStyle(el).getPropertyValue('margin-top'))
        elHeight += parseInt(window.getComputedStyle(el).getPropertyValue('margin-bottom'))

        return elHeight
    }
}

export default BannerAlternate
