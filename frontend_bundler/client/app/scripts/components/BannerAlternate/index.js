import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

const ui = {
    image: '.banner-alternate__img',
    infoboxText: '.js-infobox-text',
    infoboxCTA: '.infobox__cta',
    infoboxParagraph: '.infobox__paragraph',
}

export default class BannerAlternate extends Component {
    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.variant = this.el.getAttribute('data-variant')

        // Adjust images on resize for only mobile
        if (this.variant == 'infobox-right') {
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
            if (this.ui.infoboxParagraph) {
                this.ui.infoboxCTA.insertAdjacentElement('afterbegin', this.ui.infoboxParagraph)
            }
            let textWrapHeight = this.getFullHeight(this.ui.infoboxText) + 40
            let ctaWrapHeight = this.getFullHeight(this.ui.infoboxCTA) + 20
            this.ui.image.style.marginTop = `${textWrapHeight}px`
            this.ui.image.style.marginBottom = `${ctaWrapHeight}px`
            this.ui.image.style.height = `calc(100% - ${textWrapHeight + ctaWrapHeight}px)`
        } else {
            if (this.ui.infoboxParagraph) {
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
