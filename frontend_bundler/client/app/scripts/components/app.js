import Component from '@okiba/component'
import Carousel from './Carousel/default.js'

const components = {
  carousel: {
    selector: '.js-carousel',
    type: Carousel,
    optional: true
  },
}

export default class Application extends Component {
  constructor() {
    super({ el: document.body, components })

    this.el.classList.add('ready')
  }
}
