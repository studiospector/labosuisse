import Component from '@okiba/component'
import CarouselHero from './CarouselHero'

const components = {
  carouselHero: {
    selector: '.js-carousel-hero',
    type: CarouselHero,
    optional: true
  },
}

export default class Application extends Component {
  constructor() {
    super({ el: document.body, components })

    this.el.classList.add('ready')
  }
}
