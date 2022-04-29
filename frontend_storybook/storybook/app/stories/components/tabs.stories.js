// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderTabs from '../../views/components/tabs.twig'

import Tabs from '../../scripts/components/Tabs'

const data = {
    tabs: [
        {
            id: 'product',
            head: {
                label: 'Prodotti',
                count: 21,
            },
            entries: [
                {
                    type: 'infobox',
                    data: {
                        subtitle: 'Labo suisse: ricerca e innovazione',
                        paragraph: 'Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati.',
                    }
                },
            ]
        },
        {
            id: 'faq',
            head: {
                label: 'FAQ',
                count: 4,
            },
            entries: [
                {
                    type: 'infobox',
                    data: {
                        subtitle: '1 Labo suisse: ricerca e innovazione',
                        paragraph: '1 Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati.',
                    }
                },
            ]
        },
    ]
}

storiesOf('Components|Tabs', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-tabs',
                        type: Tabs
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Default', () => renderTabs(data))
