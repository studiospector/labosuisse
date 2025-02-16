import Component from '@okiba/component'
import { on, qs, qsa } from '@okiba/dom'

const ui = {
    productDetails: '.single-product-details',
}

class Product extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        if (this.ui.productDetails.classList.contains('product-type-variable') && (jQuery || $)) {
            this.variationChangePrice()
        }

        setTimeout(() => this.selectTertiaryVariantAlignment(), 500);
    }

    variationChangePrice = () => {
        jQuery(function($) {
            const priceSelector = qsa(['.single-product-details__summary > p.price', '.lb-header-sticky-product .lb-header-sticky-product__price'])
            const price = qs('.single-product-details__summary > p.price')
            
            if (priceSelector.length > 0) {
                $('.single-product-details__summary > form.cart')
                    .on('show_variation', (ev, data) => {
                        if ( data.price_html ) {
                            priceSelector.map(el => el.innerHTML = data.price_html)
    
                            let variantsText = []
                            const variations = qsa('.lb-product-variations select')
                            const headerVariation = qs('.lb-header-sticky-product .lb-header-sticky-product__info__variants')
    
                            variations.map(el => {
                                if (el.options[el.selectedIndex].text) {
                                    variantsText.push(el.options[el.selectedIndex].text)
                                }
                            })
    
                            if (variantsText.length > 0) {
                                headerVariation.innerText = variantsText.join(', ')
                            }
                        }
                    })
                    .on('hide_variation', (ev) => {
                        priceSelector.map(el => el.innerHTML = price.innerHTML)
                    })
            }
        })
    }

    selectTertiaryVariantAlignment = () => {
        const groupedSelect = qsa('.custom-select-group')

        if (groupedSelect.length > 0) {
            groupedSelect.forEach(elem => {
                const labels = qsa('.custom-select-label', elem)
                let labelsWidth = []
                let labelsFullWidth = []

                labels.forEach(el => {
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
