import Component from '@okiba/component'
import { qs, qsa, on, off } from '@okiba/dom'

import { setCookie, getCookie } from "../../utils/cookie";

const ui = {
    textWrapper: {
        selector: '.lb-offset-nav__content__item--image-text__text-wrapper'
    }
}

class OffsetNavCookieManagement extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.delay = Number(this.el.dataset?.delay)
        this.cookieExpire = Number(this.el.dataset?.cookieExpire)

        this.cookieClosed = getCookie(`${this.el.id}-closed`)
        this.cookieSubscribed = getCookie(`${this.el.id}-subscribed`)

        if (!Boolean(this.cookieClosed) && !Boolean(this.cookieSubscribed)) {
            setTimeout(() => window.openOffsetNav(this.el.id), this.delay);
        }

        on(this.el, 'closeOffsetNav', () => {
            if (!Boolean(this.cookieSubscribed)) {
                setCookie(`${this.el.id}-closed`, true, this.cookieExpire)
            }
        })

        mc4wp.forms.on('success', () => {
            this.handleSubmit()
        })
    }

    handleSubmit = (form, data) => {
        setCookie(`${this.el.id}-subscribed`, true, 365)
        this.cookieSubscribed = true

        if (this.ui.textWrapper) {
            this.ui.textWrapper.classList.add('lb-offset-nav__content__item--image-text__text-wrapper--hide')
        }
    }
}

export default OffsetNavCookieManagement
