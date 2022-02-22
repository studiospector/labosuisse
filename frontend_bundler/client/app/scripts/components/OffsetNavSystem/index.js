import Component from '@okiba/component'
import { on, off, qs } from '@okiba/dom'

import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'

import OffsetNav from './OffsetNav'

class OffsetNavSystem extends Component {

    constructor(props) {
        super(props)

        this.offsetNavs = {}
        this.openOffsetNavs = []

        on(this.el, 'click', this.handleClicks)
        on(window, 'keydown', this.handleCloseOnEscKeyPress)
    }

    openOffsetNav(id, initComponents) {
        const offsetNav = qs(`#${id}`)

        if (!offsetNav) return

        disableBodyScroll(this.el)
        window.getCustomScrollbar.stop()

        if (!this.offsetNavs.hasOwnProperty(id)) {
            this.offsetNavs[id] = new OffsetNav({ el: offsetNav, options: { initComponents } })
        } else if (initComponents) {
            this.offsetNavs[id].destroy()
            this.offsetNavs[id] = new OffsetNav({ el: offsetNav, options: { initComponents: true } })
        }

        this.offsetNavs[id].open()
        this.openOffsetNavs.push(id)
    }

    closeOffsetNav(id, noClose = false, outsideOffsetNav = false) {
        if (noClose && outsideOffsetNav)
            return

        if (id) {
            this.openOffsetNavs = this.openOffsetNavs.filter(entry => (entry !== id))
            this.offsetNavs[id].close()
        } else {
            this.openOffsetNavs.forEach(id => this.closeOffsetNav(id))
        }

        if (!this.openOffsetNavs.length) {
            enableBodyScroll(this.el)
            window.getCustomScrollbar.start()
        }
    }

    refreshOffsetNav(id) {
        const offsetNav = qs(`#${id}`)

        if (!offsetNav || !this.offsetNavs.hasOwnProperty(id)) return

        this.offsetNavs[id].destroy()
        this.offsetNavs[id] = new OffsetNav({ el: offsetNav, options: { initComponents: true } })
    }

    destroyOffsetNav(id) {
        if (!this.offsetNavs.hasOwnProperty(id)) return

        this.offsetNavs[id].destroy()
    }

    handleCloseOnEscKeyPress = e => {
        const key = e.key || e.keyCode
        const latestOpenOffsetNavID = this.openOffsetNavs[this.openOffsetNavs.length - 1]
        const latestOpenOffsetNav = qs(`#${latestOpenOffsetNavID}`)
        const noClose = latestOpenOffsetNav ? latestOpenOffsetNav.hasAttribute('data-no-close') : false

        if (noClose)
            return

        if (key === 'Escape' || key === 'Esc' || key === 27) {
            this.closeOffsetNav(latestOpenOffsetNavID)
        }
    }

    handleTriggersClicks(e) {
        const openTrigger = e.target.closest('.js-open-offset-nav')
        const closeTrigger = e.target.closest('.js-close-offset-nav')

        if (closeTrigger) {
            const id = openTrigger ? undefined : closeTrigger.getAttribute('data-target-offset-nav')
            this.closeOffsetNav(id)
        }

        if (openTrigger) {
            const id = openTrigger.getAttribute('data-target-offset-nav')
            this.openOffsetNav(id)
        }
    }

    handleOffsetNavClicks(e) {
        const offsetNav = e.target.closest('.lb-offset-nav')
        const noClose = offsetNav ? offsetNav.hasAttribute('data-no-close') : false
        const dialog = e.target.closest('.lb-offset-nav__dialog')

        if (!offsetNav) return
        if (!dialog) this.closeOffsetNav(offsetNav.id, noClose, true)
    }

    handleClicks = e => {
        this.handleTriggersClicks(e)
        this.handleOffsetNavClicks(e)
    }

    onDestroy() {
        off(this.el, 'click', this.handleClicks)
        off(window, 'keydown', this.handleCloseOnEscKeyPress)
    }
}

let instance

export default {
    get: forceRecreate => {
        if (!instance || forceRecreate) {
            if (instance) instance.destroy()
            instance = new OffsetNavSystem({ el: document.body })
            window.openOffsetNav = (id, initComponents) => instance.openOffsetNav(id, initComponents)
            window.closeOffsetNav = id => instance.closeOffsetNav(id)
            window.destroyOffsetNav = id => instance.destroyOffsetNav(id)
            window.refreshOffsetNav = id => instance.refreshOffsetNav(id)
        }

        return instance
    }
}
