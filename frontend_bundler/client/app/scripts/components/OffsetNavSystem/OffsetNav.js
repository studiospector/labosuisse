import Component from '@okiba/component'
import { on, off, qs } from '@okiba/dom'

import LocomotiveScroll from 'locomotive-scroll'

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

        this.headerStickyProduct = qs('.lb-header-sticky-product')

        this.ui.closeTriggers.forEach(trigger => on(trigger, 'click', this.close))

        this.scrollbar = null
        this.mobileScrollManagement()
    }

    adjustContent = () => {
        const headerHeight = qs('.lb-header').getBoundingClientRect().height
        this.el.style.paddingTop = `${headerHeight}px`
    }

    open = () => {
        qs('.lb-header').classList.add('lb-header--offsetnav-open')
        if (this.headerStickyProduct) {
            qs('.lb-header-sticky-product').classList.add('lb-header-sticky-product--offsetnav-open')
        }
        this.adjustContent()
        this.el.classList.add('is-open')
    }

    close = () => {
        this.el.classList.remove('is-open')
        qs('.lb-header').classList.remove('lb-header--offsetnav-open')
        if (this.headerStickyProduct) {
            qs('.lb-header-sticky-product').classList.remove('lb-header-sticky-product--offsetnav-open')
        }
        this.scrollbar.destroy()
    }

    onDestroy() {
        this.ui.closeTriggers.forEach(trigger => off(trigger, 'click', this.close))
        this.scrollbar.destroy()
    }

    mobileScrollManagement() {
        const navContent = qs('.lb-offset-nav__content', this.el)
        const navDialog = qs('.lb-offset-nav__dialog', this.el)

        console.log(navContent);

        // Init Locomotive Scroll
        this.scrollbar = new LocomotiveScroll({
            el: navContent,
            name: 'nav-content',
            smooth: true,
            // getSpeed: true,
            // getDirection: true,
            // reloadOnContextChange: true,
            mobile: {
                breakpoint: 0
            },
            tablet: {
                breakpoint: 0
            }
        })

        setTimeout(() => {
            this.scrollbar.update()
        }, 1000);

        console.log(this.scrollbar);



        // each time Locomotive Scroll updates, tell ScrollTrigger to update too (sync positioning)
        // scrollbar.on("scroll", ScrollTrigger.update);

        // tell ScrollTrigger to use these proxy methods for the ".js-scrollbar" element since Locomotive Scroll is hijacking things
        // ScrollTrigger.scrollerProxy(this.el, {
        //     scrollTop(value) {
        //         return arguments.length ? scrollbar.scrollTo(value, 0, 0) : scrollbar.scroll.instance.scroll.y;
        //     }, // we don't have to define a scrollLeft because we're only scrolling vertically.
        //     getBoundingClientRect() {
        //         return { top: 0, left: 0, width: window.innerWidth, height: window.innerHeight };
        //     },
        //     // LocomotiveScroll handles things completely differently on mobile devices - it doesn't even transform the container at all! So to get the correct behavior and avoid jitters, we should pin things with position: fixed on mobile. We sense it by checking to see if there's a transform applied to the container (the LocomotiveScroll-controlled element).
        //     pinType: document.querySelector(navContent).style.transform ? "transform" : "fixed"
        // });
    }
}

export default OffsetNav
