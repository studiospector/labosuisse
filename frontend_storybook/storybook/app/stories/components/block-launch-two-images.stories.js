import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
import Component from '@okiba/component'

import lazySizes from 'lazysizes'

import renderLaunchTwoImages from '../../views/components/block-launch-two-images.twig'
// import Carousel from '../../scripts/components/Carousel/default'

const data = {
  imagesLeft: {
    variants: ["parallax"],
    original: '/assets/images/launch-img-1.jpg',
    large: '/assets/images/launch-img-1.jpg',
    medium: '/assets/images/launch-img-1.jpg',
    small: '/assets/images/launch-img-1.jpg'
  },
  imagesRight: {
    variants: ["parallax"],
    original: '/assets/images/launch-img-2.jpg',
    large: '/assets/images/launch-img-2.jpg',
    medium: '/assets/images/launch-img-2.jpg',
    small: '/assets/images/launch-img-2.jpg'
  },
  infobox: {
    tagline: 'CHI SIAMO',
    subtitle: 'Labo suisse:<br>ricerca e innovazione',
    paragraph: 'Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.',
    cta: {
      type: 'button',
      label: 'Scopri il brand',
      href: '#',
      iconEnd: { name: 'arrow-right' },
      variants: ['quaternary']
    }
  }
}

storiesOf('Components|Block Launch two Images', module)
  // .addDecorator(storyFn => {
  //   useEffect(() => {
  //     const app = new Component({
  //       el: document.body,
  //       components: [{
  //         selector: '.js-block-launch-two-images',
  //         type: Carousel
  //       }]
  //     })

  //     return () => app.destroy()
  //   }, [])

  //   return storyFn()
  // })
  .add('Default', () => renderLaunchTwoImages(data))
