import Component from '@okiba/component'
import { qsa } from '@okiba/dom'

class OpenMapAppLink extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        // Set "href" to open maps app
        this.setOpenMapAppLink()
    }


    /**
     * Set link to GMap or Apple Maps APP based on device
     */
    setOpenMapAppLink = () => {
        let platform = navigator?.userAgentData?.platform || navigator?.platform

        const lat = this.el.dataset.lat
        const lng = this.el.dataset.lng

        // if we're on iOS, open in Apple Maps
        if ((platform.indexOf("iPhone") != -1) || (platform.indexOf("iPad") != -1) || (platform.indexOf("iPod") != -1)) {
            this.el.setAttribute("href", `http://maps.apple.com/?q=${lat},${lng}`)
            // else use Google
        } else {
            this.el.setAttribute("href", `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`)
        }
    }
}

export default OpenMapAppLink
