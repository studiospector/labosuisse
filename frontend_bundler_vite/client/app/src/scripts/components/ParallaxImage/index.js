import Component from '@okiba/component'
import { qs, on } from '@okiba/dom'

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const ui = {}

class ParallaxImage extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.init()
    }

    init = () => {
      const image = this.el.querySelector("img")
    
      gsap.to(image, {
        y: () => 0,
        ease: "none",
        scrollTrigger: {
          trigger: this.el,
          start: "top bottom",
          end: "bottom 10%",
          scrub: true,
          pin: false,
          markers: true,
          invalidateOnRefresh: true,
        },
      })
    }
}

export default ParallaxImage
