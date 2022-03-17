import Component from '@okiba/component'
import { on } from '@okiba/dom'

import axios from 'axios'

const ui = {
    renderEl: '.js-geolocation-render-element',
    triggerEl: '.js-geolocation-trigger-element',
    loader: '.js-geolocation-loader'
}

class Geolocation extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.location = null

        on(this.ui.triggerEl, 'click', this.geolocation)
    }


    /**
     * Init Geolocation of current User and show Marker on Map
     */
    geolocation = () => {
        // Add loader
        this.addLoader()

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {

                // Get location
                this.getCurrentLocation(position.coords.latitude, position.coords.longitude).then((res) => { this.location = res }).then(() => {
                    const city = this.location.results[0].address_components[2].long_name
                    this.ui.renderEl.value = city
                    this.ui.renderEl.updateState(this.ui.renderEl.value)
                })

                // Remove loader
                this.removeLoader()
            },
                () => {
                    handleLocationError(true)
                })
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false)
        }

        const handleLocationError = (browserHasGeolocation) => {
            // Remove loader
            this.removeLoader()

            alert(browserHasGeolocation ? "Error: The Geolocation service failed." : "Error: Your browser doesn't support geolocation.")
        }
    }


    /**
     * Get location by lat and lng
     * 
     * @param {Number} lat Latitude
     * @param {Number} lng Longitude
     * 
     * @returns location object
     */
    getCurrentLocation = async (lat, lng) => {
        let location = null
        
        try {
            const { data } = await axios.get('https://maps.googleapis.com/maps/api/geocode/json', {
                params: {
                    latlng: `${lat}, ${lng}`,
                    key: 'AIzaSyCG7SmW_OVvWwj1ngGzqBdLhOGgpXTpBnM'
                }              
            })
            location = data
        } catch (error) {
            console.error(error);
        }

        return location
    }


    /**
     * Add Loading Overlay to Map
     */
    addLoader = () => {
        this.ui.loader.classList.add('caffeina-base-loader--loading')
    }


    /**
     * Remove Loading Overlay to Map
     */
    removeLoader = () => {
        this.ui.loader.classList.remove('caffeina-base-loader--loading')
    }
}

export default Geolocation
