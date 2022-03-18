// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'

// Components
import render from '../../views/components/checklist.twig'

const dataDefault = {
    items: [
        {
            title: "Analisi del capello e del cuoio capelluto",
        },
        {
            title: "Analisi della pelle",
        },
        {
            title: "Prova Labo Make Up",
        },
        {
            title: "Prova dei trattamenti",
        },
        {
            title: "Appuntamenti di controllo",
        },
        {
            title: "Promozioni e distribuzione campioncini",
        },
    ]
}

storiesOf('Components|Checklist', module)
    .add('Default', () => render(dataDefault))
