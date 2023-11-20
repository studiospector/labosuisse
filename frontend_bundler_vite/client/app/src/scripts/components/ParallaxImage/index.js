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
        y: -30,
        ease: "none",
        duration: 2,
        scale: 1.08,
        scrollTrigger: {
          trigger: this.ui.picture,
          start: "top 80%",
          end: "80% 118px",
          scrub: 3,
          pin: false,
          markers: false,
          invalidateOnRefresh: true,
        },
      })
    }
}

export default ParallaxImage
