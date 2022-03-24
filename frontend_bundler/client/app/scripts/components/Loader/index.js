import Component from '@okiba/component'
import { on, qsa } from '@okiba/dom'

class Loader extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        this.onLoad()
        on(window, 'beforeunload', this.onLeave)
    }

    onLoad = () => {
        this.el.classList.remove('lb-loader--loading')
    }

    onLeave = () => {
        this.el.classList.add('lb-loader--leaving')
    }
}

export default Loader
