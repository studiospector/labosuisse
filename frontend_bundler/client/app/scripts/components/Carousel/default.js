import Component from '@okiba/component'
// import Swiper, { Pagination } from 'swiper'
import Swiper, { Pagination } from 'swiper/swiper-bundle';

const defaults = {
  speed: 400
}

const ui = {
  pagination: '.swiper-pagination'
}

export default class Carousel extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui })

    this.swiper = new Swiper(this.el, {
      ...defaults,
      pagination: {
        el: this.ui.pagination,
      },
    });
  }

  onDestroy() {
    this.swiper.destroy()
  }
}
