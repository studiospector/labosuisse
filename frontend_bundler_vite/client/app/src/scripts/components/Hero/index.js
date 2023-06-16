import Component from '@okiba/component'
import { qs, on } from '@okiba/dom'

class Hero extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        // Adjust images on resize for only mobile
        setTimeout(() => {
            this.adjustImageOffsetDispatcher()
        }, 100);
        on(window, 'resize', this.adjustImageOffsetDispatcher)
    }

    adjustImageOffsetDispatcher = () => {
        let matchMedia = window.matchMedia("screen and (max-width: 767px)")
        this.adjustImageOffset(matchMedia)
    }

    adjustImageOffset = (e) => {
        if (e.matches) {
            let textWrapHeight = this.getFullHeight(qs('.js-infobox-text', this.el)) + 40
            qs('.hero__img', this.el).style.marginTop = `${textWrapHeight}px`
            qs('.hero__img', this.el).style.height = `calc(100% - ${textWrapHeight}px)`
        } else {
            qs('.hero__img', this.el).style.marginTop = `0px`
            qs('.hero__img', this.el).style.height = `100%`
        }
    }

    getFullHeight(el) {
        let elHeight = el.offsetHeight

        elHeight += parseInt(window.getComputedStyle(el).getPropertyValue('margin-top'))
        elHeight += parseInt(window.getComputedStyle(el).getPropertyValue('margin-bottom'))

        return elHeight
    }
}

export default Hero
