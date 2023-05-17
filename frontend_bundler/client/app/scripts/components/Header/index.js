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

        this.timeout = 0
        this.lastScrollTop = 0

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
        window.getCustomScrollbar.on('scroll', this.handleScroll)
    }

    handleScroll = (instance) => {
        if (this.timeout) {
            clearTimeout(this.timeout)
        }

        this.timeout = setTimeout(() => {
            let scrollTop = instance.scroll.y

            if (scrollTop > this.lastScrollTop && instance.scroll.y > 100 && instance.direction == 'down') {
                this.el.classList.add('lb-header--hide')
                if (this.headerProduct) {
                    this.headerProduct.classList.remove('lb-header-sticky-product--adjust')
                }
            } else if (scrollTop < this.lastScrollTop && instance.direction == 'up') {
                this.el.classList.remove('lb-header--hide')
                if (this.headerProduct) {
                    this.headerProduct.classList.add('lb-header-sticky-product--adjust')
                }
            }

            this.lastScrollTop = scrollTop <= 0 ? 0 : scrollTop
        }, 10)
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
