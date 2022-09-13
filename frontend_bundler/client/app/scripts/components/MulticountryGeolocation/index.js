import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'
import { debounce } from '@okiba/functions'

import axiosClient from '../HTTPClient'

import templateLoader from '../../utils/templateLoader';

const ui = {
    // items: {
    //     selector: '',
    //     asArray: true
    // }
}

class MulticountryGeolocation extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.getGeolocationData().then((res) => {
            this.render(res)
            window.openOffsetNav('lb-offsetnav-multicountry-geolocation')
        })
    }


    /**
     * Get Geolocation data
     * 
     * @returns Data fetched
     */
    getGeolocationData = async () => {
        let response = []
        try {
            let { data } = await axiosClient.get('/wp-json/v1/multicountry-geolocation')
            response = data
        } catch (error) {
            console.error(error)
        }

        return response
    }


    /**
     * Render content in popup
     */
    render = async (data) => {
        // Load twig template
        const template = await templateLoader('components/offset-nav/templates/multicountry/geolocation-content.twig')
        const html = template.render(data)

        this.el.innerHTML = html
    }
}

export default MulticountryGeolocation
