import Component from '@okiba/component'
import { on, qs } from '@okiba/dom'

class Loader extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        this.onLoad()

        // on(window, 'beforeunload', this.onLeave)
    }

    onLoad = () => {
        this.el.classList.remove('lb-loader--loading')
        qs('body').classList.remove('is-loading')
    }

    onLeave = () => {
        this.el.classList.add('lb-loader--leaving')
    }
}

export default Loader
