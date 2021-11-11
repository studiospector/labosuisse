// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderCarouselHero from '../../views/components/carousel-hero.twig'
import CarouselHero from '../../scripts/components/CarouselHero'

const dataRightInfobox = {
    slides: [
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                large: '/assets/images/carousel-hero-img.jpg',
                medium: '/assets/images/carousel-hero-img.jpg',
                small: '/assets/images/carousel-hero-img.jpg'
            },
            infobox: {
                side: 'right',
                tagline: 'CRESCINA TRANSDERMIC RAPID-INTENSIVE',
                title: 'Favorisce la crescita<br>fisiologica dei capelli',
                cta: {
                    href: '#',
                    label: 'Scopri la linea',
                    variants: ['secondary']
                }
            }
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                large: '/assets/images/carousel-hero-img.jpg',
                medium: '/assets/images/carousel-hero-img.jpg',
                small: '/assets/images/carousel-hero-img.jpg'
            },
            infobox: {
                side: 'right',
                tagline: 'CRESCINA TRANSDERMIC RAPID-INTENSIVE',
                title: 'Favorisce la crescita<br>fisiologica dei capelli',
                paragraph: 'Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.',
                cta: {
                    href: '#',
                    label: 'Scopri la linea',
                    variants: ['secondary']
                }
            }
        },
    ]
}

const dataLeftInfobox = {
    slides: [
        {
            images: {
                original: '/assets/images/carousel-hero-img-2.jpg',
                large: '/assets/images/carousel-hero-img-2.jpg',
                medium: '/assets/images/carousel-hero-img-2.jpg',
                small: '/assets/images/carousel-hero-img-2.jpg'
            },
            infobox: {
                side: 'left',
                tagline: 'CRESCINA TRANSDERMIC RAPID-INTENSIVE',
                title: 'Favorisce la crescita<br>fisiologica dei capelli',
                paragraph: 'Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.',
                cta: {
                    href: '#',
                    label: 'Scopri la linea',
                    variants: ['secondary']
                }
            }
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img-2.jpg',
                large: '/assets/images/carousel-hero-img-2.jpg',
                medium: '/assets/images/carousel-hero-img-2.jpg',
                small: '/assets/images/carousel-hero-img-2.jpg'
            },
            infobox: {
                side: 'left',
                tagline: 'CRESCINA TRANSDERMIC RAPID-INTENSIVE',
                title: 'Favorisce la crescita<br>fisiologica dei capelli',
                cta: {
                    href: '#',
                    label: 'Scopri la linea',
                    variants: ['secondary']
                }
            }
        },
    ]
}

storiesOf('Components|Carousel Hero', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-carousel-hero',
                        type: CarouselHero
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Right Infobox', () => renderCarouselHero(dataRightInfobox))
    .add('Left Infobox', () => renderCarouselHero(dataLeftInfobox))
