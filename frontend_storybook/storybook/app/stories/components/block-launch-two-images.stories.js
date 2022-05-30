// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderLaunchTwoImages from '../../views/components/launch-two-images.twig'
import AnimationReveal from '../../scripts/components/AnimationReveal'

const data = {
    imagesLeft: {
        variants: ["parallax"],
        original: 'https://via.placeholder.com/570x700',
        lg: 'https://via.placeholder.com/570x700',
        md: 'https://via.placeholder.com/570x700',
        sm: 'https://via.placeholder.com/570x700',
        xs: 'https://via.placeholder.com/570x700'
    },
    imagesRight: {
        variants: ["parallax"],
        original: 'https://via.placeholder.com/570x700',
        lg: 'https://via.placeholder.com/570x700',
        md: 'https://via.placeholder.com/570x700',
        sm: 'https://via.placeholder.com/570x700',
        xs: 'https://via.placeholder.com/570x700'
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
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-animation-reveal',
                        type: AnimationReveal,
                        optional: true
                    },
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Default', () => renderLaunchTwoImages(data))
