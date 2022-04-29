import Component from '@okiba/component'
import { on, off } from '@okiba/dom'

const ui = {
    tabs: {
        selector: '.lb-tabs__tab',
        asArray: true
    },
    panes: {
        selector: '.lb-tabs__pane',
        asArray: true
    }
}

class Tabs extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.current = this.ui.tabs.findIndex(tab => tab.classList.contains('is-active'))

        this.ui.tabs.forEach(tab => on(tab, 'click', this.handleTabChange))
    }

    handleTabChange = ({ target }) => {
        const tab = target.closest('[data-target]')
        const paneId = tab ? tab.getAttribute('data-target') : null
        const targetIndex = paneId ? this.ui.panes.findIndex(pane => (pane.id === paneId)) : -1

        if (targetIndex === -1) return

        this.ui.tabs[this.current].classList.remove('is-active')
        this.ui.panes[this.current].classList.remove('is-active')

        this.current = targetIndex

        this.ui.tabs[this.current].classList.add('is-active')
        this.ui.panes[this.current].classList.add('is-active')
    }

    onDestroy() {
        this.ui.tabs.forEach(tab => off(tab, 'click', this.handleTabChange))
    }
}

export default Tabs
