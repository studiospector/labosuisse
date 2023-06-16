import Component from '@okiba/component'
import { on, qs } from '@okiba/dom'

class Loader extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        qs('body').classList.remove('is-loading')

        this.onLoad()
        on(window, 'beforeunload', this.onLeave)
    }

    // onLoad = debounce(() => {
    //     this.el.classList.remove('lb-loader--loading')
    // }, 300)

    onLoad = () => {
        this.el.classList.remove('lb-loader--loading')
    }

    onLeave = () => {
        this.el.classList.add('lb-loader--leaving')
    }
}

export default Loader
