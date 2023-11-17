import Component from '@okiba/component'
import { qs, on } from '@okiba/dom'

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const ui = {
  picture: '.lb-picture',
  image: 'img',
}

class ParallaxImage extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.init()
    }

    init = () => {
      gsap.to(this.ui.image, {
        y: -50,
        ease: "none",
        scrollTrigger: {
          trigger: this.ui.picture,
          start: "top bottom",
          end: "bottom 10%",
          scrub: true,
          pin: false,
          markers: false,
          invalidateOnRefresh: true,
        },
      })
    }
}

export default ParallaxImage
