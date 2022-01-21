import Component from '@okiba/component'
import { on, off, qs } from '@okiba/dom'

import { stickyHeader } from '../MenuMobile/animations';

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

        this.adjustContent()

        this.ui.closeTriggers.forEach(trigger => on(trigger, 'click', this.close))
    }

    adjustContent = () => {
        const headerHeight = qs('.lb-header').getBoundingClientRect().height
        this.el.style.paddingTop = `${headerHeight}px`
    }

    open = () => {
        this.el.classList.add('is-open')
    }

    close = () => {
        this.el.classList.remove('is-open')
    }

    onDestroy() {
        this.ui.closeTriggers.forEach(trigger => off(trigger, 'click', this.close))
    }
}

export default OffsetNav
