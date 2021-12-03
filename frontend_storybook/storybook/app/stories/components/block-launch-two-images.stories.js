// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderLaunchTwoImages from '../../views/components/launch-two-images.twig'

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
            url: '#',
            title: 'Scopri il brand',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    }
}

storiesOf('Components|Block Launch two Images', module)
    .add('Default', () => renderLaunchTwoImages(data))
