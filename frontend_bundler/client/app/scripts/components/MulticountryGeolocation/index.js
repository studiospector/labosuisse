import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'
import { debounce } from '@okiba/functions'

const ui = {
    // items: {
    //     selector: '',
    //     asArray: true
    // }
}

class MulticountryGeolocation extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        console.log('MulticountryGeolocation');

        setTimeout(() => {
            window.openOffsetNav('lb-offsetnav-multicountry-geolocation')
        }, 200)
    }

}

export default MulticountryGeolocation
