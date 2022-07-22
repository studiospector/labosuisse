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

        this.headerStickyProduct = qs('.lb-header-sticky-product')

        this.ui.closeTriggers.forEach(trigger => on(trigger, 'click', this.close))

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
    }

    onDestroy() {
        this.ui.closeTriggers.forEach(trigger => off(trigger, 'click', this.close))
    }

    mobileScrollManagement() {
        var _overlay = qs('.lb-offset-nav__content', this.el);
        var _clientY = null; // remember Y position on touch start
        
        _overlay.addEventListener('touchstart', function (event) {
            if (event.targetTouches.length === 1) {
                // detect single touch
                _clientY = event.targetTouches[0].clientY;
            }
        }, false);
        
        _overlay.addEventListener('touchmove', function (event) {
            if (event.targetTouches.length === 1) {
                // detect single touch
                disableRubberBand(event);
            }
        }, false);
        
        function disableRubberBand(event) {
            var clientY = event.targetTouches[0].clientY - _clientY;
        
            if (_overlay.scrollTop === 0 && clientY > 0) {
                // element is at the top of its scroll
                event.preventDefault();
            }
        
            if (isOverlayTotallyScrolled() && clientY < 0) {
                //element is at the top of its scroll
                event.preventDefault();
            }
        }
        
        function isOverlayTotallyScrolled() {
            // https://developer.mozilla.org/en-US/docs/Web/API/Element/scrollHeight#Problems_and_solutions
            return _overlay.scrollHeight - _overlay.scrollTop <= _overlay.clientHeight;
        }
    }
}

export default OffsetNav
