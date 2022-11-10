import Component from '@okiba/component'
import { qsa, qs, on } from '@okiba/dom'

const ui = {
    iconOpenSearch: '.lb-header__top__icons__item--search .lb-open-search',
    searchForm: '.lb-header__top__icons__item--search .lb-search-form',
    searchFormInput: '.lb-header__top__icons__item--search .lb-search-form .lb-search-autocomplete__input'
}

class Header extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.curScroll = null
        this.prevScroll = window.scrollY
        this.direction = 0
        this.prevDirection = 0
        this.firstUp = false
        this.tempScrollUp = null

        this.headerProduct = qs('.lb-header-sticky-product')
        
        setTimeout(() => this.adjustMainContent(), 100)
        // on(window, 'resize', this.adjustMainContent)
        
        setTimeout(() => this.adjustMenu(), 200)
        // on(window, 'resize', this.adjustMenu)

        setTimeout(() => on([this.ui.iconOpenSearch, qs('.lb-header__top__icons__item--search .custom-input .custom-input__icon--prev')], 'click', this.toggleSearch), 1000)
        on(document, 'click', this.closeSearchOnClickOutside)

        if (this.ui.searchFormInput.value.length > 0) {
            this.toggleSearch()
        }

        // On scroll trigger with Locomotive
        window.getCustomScrollbar.on('scroll', this.checkScroll)
    }


    /**
     * Find the direction of scroll:
     * 0 - initial
     * 1 - up
     * 2 - down
     */
    checkScroll = (instance) => {
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
                // setTimeout(() => {
                //     let matchMedia = window.matchMedia("screen and (max-width: 767px)")
                //     if (!matchMedia.matches) {
                //         this.headerProduct.style.top = `${this.el.getBoundingClientRect().height}px`
                //     }
                // }, 550);
            }
            this.prevDirection = direction
        } else if (direction === 1) {
            this.el.classList.remove('lb-header--hide')
            if (this.headerProduct) {
                this.headerProduct.classList.add('lb-header-sticky-product--adjust')
                // setTimeout(() => {
                //     let matchMedia = window.matchMedia("screen and (max-width: 767px)")
                //     if (!matchMedia.matches) {
                //         this.headerProduct.style.top = `${this.el.getBoundingClientRect().height}px`
                //     }
                // }, 550);
            }
            this.prevDirection = direction
        }
    }


    adjustMenu = () => {
        const headerHeight = this.el.getBoundingClientRect().height
        const elems = qsa('.lb-menu__overlay')
        elems.forEach(elem => {
            elem.style.top = `${headerHeight}px`
        })
    }


    adjustMainContent = () => {
        const headerHeight = this.el.getBoundingClientRect().height
        const mainContent = qs('#content')
        mainContent.style.paddingTop = `${headerHeight}px`
    }

    toggleSearch = (ev) => {
        this.ui.searchForm.classList.toggle('lb-search-form--open')
    }

    closeSearchOnClickOutside = (ev) => {
        if (ev.target.closest('.lb-search-form') !== this.ui.searchForm && ev.target.closest('.lb-open-search') !== this.ui.iconOpenSearch) {
            this.ui.searchForm.classList.remove('lb-search-form--open')
        }
    }
}

export default Header
