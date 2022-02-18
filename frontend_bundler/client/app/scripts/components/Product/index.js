import Component from '@okiba/component'
import { qsa, qs, on, off } from '@okiba/dom'

class Product extends Component {
    constructor({ options, ...props }) {
        super({ ...props })

        this.selectTertiaryVariantAlignment()
    }

    selectTertiaryVariantAlignment = () => {
        const groupedSelect = qsa('.custom-select-group')

        if (groupedSelect.length > 0) {
            groupedSelect.forEach(elem => {
                const labels = qsa('.custom-select-label', elem)
                let labelsWidth = []
                let labelsFullWidth = []
                labels.forEach( el => {
                    labelsWidth.push(el.offsetWidth)
                    labelsFullWidth.push(this.getFullWidth(el))
                })

                labels.forEach(elem => {
                    elem.style.minWidth = `${Math.max(...labelsWidth)}px`
                })

                const items = qsa('.custom-select-items .custom-select-items__item', elem)

                items.forEach(elem => {
                    elem.style.paddingLeft = `${Math.max(...labelsFullWidth) + 16.5 - 26}px`
                })
            })
        }
    }

    getFullWidth(el) {
        let elWidth = el.offsetWidth

        elWidth += parseInt(window.getComputedStyle(el).getPropertyValue('margin-left'))
        elWidth += parseInt(window.getComputedStyle(el).getPropertyValue('margin-right'))

        return elWidth
    }
}

export default Product
