import Component from '@okiba/component'
import { on } from '@okiba/dom'

const ui = {
    textWrap: '.js-infobox-text',
}

class CardsGrid extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.maxHeight = 0

        this.adjustTextWrapHeightDispatcher()
        on(window, 'resize', this.adjustTextWrapHeightDispatcher)
    }

    adjustTextWrapHeightDispatcher = () => {
        let matchMedia = window.matchMedia("screen and (min-width: 1200px)")
        this.adjustTextWrapHeight(matchMedia)
    }

    adjustTextWrapHeight = (e) => {
        if (e.matches) {
            this.ui.textWrap.filter(el => {
                let tempMax = el.offsetHeight

                if (tempMax > this.maxHeight) {
                    this.maxHeight = tempMax
                }
            })

            this.ui.textWrap.forEach(el => {
                el.style.height = `${this.maxHeight}px`
            })
        } else {
            this.ui.textWrap.forEach(el => {
                el.style.height = 'auto'
            })
        }
    }
}

export default CardsGrid
