import Component from '@okiba/component'
import { on, off, qs } from '@okiba/dom'

// import Select from '../Select'
// import AsyncSearch from '../AsyncSearch'
// import Accordion from '../Accordion'
// import TabView from '../TabView'
// import DatePicker from '../DatePicker'

const ui = {
    closeTriggers: {
        selector: '.js-close-offset-nav',
        asArray: true
    }
}

const components = [
    //   { selector: '.Select', type: Select },
    //   { selector: '.AsyncSearch', type: AsyncSearch },
    //   { selector: '.Accordion', type: Accordion },
    //   { selector: '.TabView', type: TabView },
    //   { selector: '.Datepicker', type: DatePicker }
]

class OffsetNav extends Component {
    
    constructor({ options = {}, ...props }) {
        super({ ...props, ui, components: options.initComponents ? components : null })

        this.isPopup = 'offsetNavPopup' in this.el.dataset ? true : false

        this.headerStickyProduct = qs('.lb-header-sticky-product')

        this.ui.closeTriggers.forEach(trigger => on(trigger, 'click', this.close))
    }

    adjustContent = () => {
        let matchMedia = window.matchMedia("screen and (max-width: 767px)")
        let elem = '.lb-header'

        if (matchMedia.matches) {
            elem = '.lb-header .lb-header__top'
        }

        const headerHeight = qs(elem).getBoundingClientRect().height
        this.el.style.paddingTop = `${headerHeight}px`
    }

    open = () => {
        if (!this.isPopup) {
            qs('.lb-header').classList.add('lb-header--offsetnav-open')
        }
        if (this.headerStickyProduct) {
            qs('.lb-header-sticky-product').classList.add('lb-header-sticky-product--offsetnav-open')
        }
        setTimeout(() => this.adjustContent(), 450);
        setTimeout(() => this.el.classList.add('is-open'), 500);
    }

    close = () => {
        this.el.classList.remove('is-open')
        if (!this.isPopup) {
            qs('.lb-header').classList.remove('lb-header--offsetnav-open')
        }
        if (this.headerStickyProduct) {
            qs('.lb-header-sticky-product').classList.remove('lb-header-sticky-product--offsetnav-open')
        }
    }

    onDestroy() {
        this.ui.closeTriggers.forEach(trigger => off(trigger, 'click', this.close))
    }
}

export default OffsetNav
