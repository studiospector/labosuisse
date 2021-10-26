import Component from '@okiba/component'
import {qs, on, off} from '@okiba/dom'

import Swiper, { Pagination } from 'swiper/swiper-bundle';

const defaults = {
  speed: 400
}

const ui = {
  slides: {
    selector: '.carousel-hero__slide',
    asArray: true
  },
  pagination: '.swiper-pagination'
}

export default class CarouselHero extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui })

    // Init swiper
    this.swiper = new Swiper(this.el, {
      ...defaults,
      pagination: {
        el: this.ui.pagination,
      },
    });
    
    // Adjust images on resize for only mobile
    let matchMedia = window.matchMedia("screen and (max-width: 768px)")
    this.adjustImageOffset(matchMedia)
    matchMedia.addListener(this.adjustImageOffset)
  }

  adjustImageOffset = (e) => {
    if (e.matches) {
      this.ui.slides.forEach(el => {
        let textWrapHeight = this.getFullHeight(qs('.js-infobox-text', el)) + 40
        qs('.hero__img', el).style.marginTop = `${textWrapHeight}px`
      })
    } else {
      this.ui.slides.forEach(el => {
        qs('.hero__img', el).style.marginTop = `0px`
      })
    }
  }

  getFullHeight(el) {
    let elHeight = el.offsetHeight

    elHeight += parseInt(window.getComputedStyle(el).getPropertyValue('margin-top'))
    elHeight += parseInt(window.getComputedStyle(el).getPropertyValue('margin-bottom'))

    return elHeight
  }

  onDestroy() {
    this.swiper.destroy()
  }
}
