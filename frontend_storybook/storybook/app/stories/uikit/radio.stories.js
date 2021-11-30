// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderRadio from '../../views/components/fields/radio.twig'

// Vertical
const dataVertical = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: false,
    options: [
        { label: "Lorem", value: "lorem", checked: true },
        { label: "Ipsum", value: "ipsum" },
        { label: "Dolor", value: "dolor" },
        { label: "Sit", value: "sit" },
        { label: "Amet", value: "amet" },
    ],
    variants: ['vertical'],
}

// Horizontal
const dataHorizontal = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: false,
    options: [
        { label: "Lorem", value: "lorem", checked: true },
        { label: "Ipsum", value: "ipsum" },
        { label: "Dolor", value: "dolor" },
        { label: "Sit", value: "sit" },
        { label: "Amet", value: "amet" },
    ],
    variants: ['horizontal'],
}

// Disabled
const dataDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
        { label: "Lorem", value: "lorem" },
        { label: "Ipsum", value: "ipsum" },
        { label: "Dolor", value: "dolor", disabled: true },
        { label: "Sit", value: "sit", disabled: true },
        { label: "Amet", value: "amet" },
    ],
    variants: ['vertical'],
}

storiesOf('UIKit|Radio', module)
    // Vertical
    .add('Vertical', () => renderRadio(dataVertical))
    // Horizontal
    .add('Horizontal', () => renderRadio(dataHorizontal))
    // Disabled
    .add('Disabled', () => renderRadio(dataDisabled))
