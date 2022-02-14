import Component from '@okiba/component'
import { qsa, qs, on, off } from '@okiba/dom'

export default class Header extends Component {
    constructor({ options, ...props }) {
        super({ ...props })
        
        this.curScroll = null
        this.prevScroll = window.scrollY
        this.direction = 0
        this.prevDirection = 0
        this.firstUp = false
        this.tempScrollUp = null

        this.headerProduct = qs('.lb-header-sticky-product')
        
        // On scroll trigger with Locomotive
        const customScrollbar = window.getCustomScrollbar
        customScrollbar.on('scroll', this.checkScroll)

        setTimeout(() => this.adjustMainContent(), 100)
        on(window, 'resize', this.adjustMainContent)

        setTimeout(() => this.adjustMenu(), 100)
        on(window, 'resize', this.adjustMenu)
    }


    checkScroll = (instance) => {
        /**
         * Find the direction of scroll:
         * 0 - initial
         * 1 - up
         * 2 - down
         */
        this.curScroll = instance.scroll.y

        // Scrolled up
        if (this.curScroll > this.prevScroll) {
            this.direction = 2
            if (this.direction != this.prevDirection) {
                this.toggleHeader(this.direction, this.curScroll)
            }
            this.firstUp = false

        // Scrolled down
        } else if (this.curScroll < this.prevScroll) {
            this.direction = 1

            if (this.firstUp == false) {
                this.firstUp = true
                this.tempScrollUp = this.curScroll - 100
            }
            
            if (this.firstUp == true && (this.curScroll < this.tempScrollUp)) {
                if (this.direction != this.prevDirection) {
                    this.toggleHeader(this.direction, this.curScroll)
                }
            } else {
                return
            }
        }

        this.prevScroll = this.curScroll
    }


    toggleHeader(direction, curScroll) {
        const headerHeight = this.el.getBoundingClientRect().height
        if (direction === 2 && curScroll > headerHeight) {
            this.el.classList.add('lb-header--hide')
            if (this.headerProduct) {
                this.headerProduct.classList.remove('lb-header-sticky-product--adjust')
            }
            this.prevDirection = direction
        } else if (direction === 1) {
            this.el.classList.remove('lb-header--hide')
            if (this.headerProduct) {
                this.headerProduct.classList.add('lb-header-sticky-product--adjust')
            }
            this.prevDirection = direction
        }
    }


    adjustMenu = () => {
        const headerHeight = this.el.getBoundingClientRect().height
        const elems = qsa('.lb-menu__background, .lb-menu__overlay, lb-menu--desktop .lb-menu__submenu')
        elems.forEach(elem => {
            elem.style.top = `${headerHeight}px`
        })
    }


    adjustMainContent = () => {
        const headerHeight = this.el.getBoundingClientRect().height
        const mainContent = qs('#content')
        mainContent.style.paddingTop = `${headerHeight}px`
    }
}
