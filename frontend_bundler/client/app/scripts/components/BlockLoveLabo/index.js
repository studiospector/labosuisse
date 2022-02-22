import Component from '@okiba/component'

const ui = {
    scroller: '.lovelabo__grid .row',
    items: {
        selector: '.lovelabo__grid .lovelabo__img',
        asArray: true
    },
}

class BlockLoveLabo extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        // Only for Variant Full
        if (this.el.classList.contains('lovelabo--full')) {
            this.placeScoller()
        }
    }

    placeScoller() {
        const secondItemsCoordinates = this.ui.items[1].getBoundingClientRect()
        this.ui.scroller.scrollLeft = secondItemsCoordinates.left / 2
    }
}

export default BlockLoveLabo
