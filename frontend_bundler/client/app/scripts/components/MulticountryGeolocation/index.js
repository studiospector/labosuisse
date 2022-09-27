import Component from '@okiba/component'
import { qs, qsa, on, off } from '@okiba/dom'
import { debounce } from '@okiba/functions'

import axiosClient from '../HTTPClient'

import templateLoader from '../../utils/templateLoader';

import { setCookie, getCookie } from "../../utils/cookie";

const ui = {
    // items: {
    //     selector: '',
    //     asArray: true
    // }
}

class MulticountryGeolocation extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.cookie = getCookie('lb_multicountry_geolocation')

        if (!Boolean(this.cookie)) {
            this.getGeolocationData().then((res) => {
                console.log(res);
                if (res) {
                    this.render(res).then(() => {
                        window.openOffsetNav('lb-offsetnav-multicountry-geolocation')
            
                        const buttons = qsa('#lb-multicountry-geolocation-content .button')
                        on(buttons, 'click', this.setCookieOnClick)
                    })
                }
            })
        }
    }

    /**
     * Get Geolocation data
     * 
     * @returns Data fetched
     */
    getGeolocationData = async () => {
        let response = []
        
        try {
            const currentLang = getCookie('wp-wpml_current_language')

            if (typeof currentLang !== 'string') {
                throw "currentLang: Not a string";
            }
            
            let { data } = await axiosClient.get('/wp-json/v1/multicountry-geolocation', {params: {lang: currentLang}})
            response = data
        } catch (error) {
            console.error(error)
        }

        return response
    }

    /**
     * Set cookie
     */
    setCookieOnClick = (ev) => {
        setCookie('lb_multicountry_geolocation', true, 365)
    }

    /**
     * Render content in popup
     */
    render = async (data) => {
        // Load twig template
        const template = await templateLoader('components/offset-nav/templates/multicountry/geolocation-content.twig')
        const html = template.render(data.data)

        this.el.innerHTML = html
    }
}

export default MulticountryGeolocation
