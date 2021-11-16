// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderCarouselPosts from '../../views/components/carousel-posts.twig'

import CarouselPosts from '../../scripts/components/CarouselPosts'

const dataFull = {
    leftCard: {
        // color: '#f5f5f5',
        infobox: {
            subtitle: 'Titolo di due righe come massimo',
            paragraph: 'Nella sezione News, puoi trovare i nostri contenuti editoriali: sfoglia gli articoli, prendi ispirazione e lasciati guidare dal team Labo.',
            cta: {
                href: '#',
                label: 'Vai a news',
                variants: ['secondary']
            }
        },
        variants: ['type-8']
    },
    items: [
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
    ],
    variants: ['full']
}

const dataOnePost = {
    leftCard: {
        // color: '#f5f5f5',
        infobox: {
            subtitle: 'Titolo di due righe come massimo',
            paragraph: 'Nella sezione News, puoi trovare i nostri contenuti editoriali: sfoglia gli articoli, prendi ispirazione e lasciati guidare dal team Labo.',
            cta: {
                href: '#',
                label: 'Vai a news',
                variants: ['secondary']
            }
        },
        variants: ['type-8']
    },
    items: [
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
    ],
    variants: ['one-post']
}

const dataTwoPosts = {
    leftCard: {
        // color: '#f5f5f5',
        infobox: {
            subtitle: 'Titolo di due righe come massimo',
            cta: {
                href: '#',
                label: 'Vai a news',
                variants: ['secondary']
            }
        },
        variants: ['type-8']
    },
    items: [
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                large: '/assets/images/card-img-6.jpg',
                medium: '/assets/images/card-img-6.jpg',
                small: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    href: '#',
                    label: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2']
        },
    ],
    variants: ['two-posts']
}

storiesOf('Components|Carousel Posts', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-carousel-posts',
                        type: CarouselPosts
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Full', () => renderCarouselPosts(dataFull))
    .add('One Post per Slide', () => renderCarouselPosts(dataOnePost))
    .add('Two Posts per Slide', () => renderCarouselPosts(dataTwoPosts))
