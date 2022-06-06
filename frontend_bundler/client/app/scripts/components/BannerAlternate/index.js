import Component from '@okiba/component'
import { on } from '@okiba/dom'

const ui = {
    image: '.banner-alternate__img',
    infoboxText: '.js-infobox-text',
    infoboxCTA: '.infobox__cta',
    infoboxParagraph: {
        selector: '.infobox__paragraph',
        asArray: true,
    },
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

            if (this.ui.infoboxParagraph.length > 0 && this.ui.infoboxCTA) {
                this.ui.infoboxParagraph.reverse().forEach(el => {
                    this.ui.infoboxCTA.insertAdjacentElement('afterbegin', el)
                })
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
            if (this.ui.infoboxParagraph.length > 0 && this.ui.infoboxText) {
                this.ui.infoboxParagraph.reverse().forEach(el => {
                    this.ui.infoboxText.insertAdjacentElement('afterend', el)
                })
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
