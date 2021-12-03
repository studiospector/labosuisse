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
    options: [
        { label: "Option 1", value: "option-1", checked: true },
        { label: "Option 2", value: "option-2" },
        { label: "Option 3", value: "option-3" },
        { label: "Option 4", value: "option-4" },
    ],
    variants: ['vertical'],
}

// Horizontal
const dataHorizontal = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
        { label: "Option 1", value: "option-1", checked: true },
        { label: "Option 2", value: "option-2" },
        { label: "Option 3", value: "option-3" },
        { label: "Option 4", value: "option-4" },
    ],
    variants: ['horizontal'],
}

// Disabled
const dataDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
        { label: "Option 1", value: "option-1" },
        { label: "Option 2", value: "option-2", disabled: true },
        { label: "Option 3", value: "option-3", disabled: true },
        { label: "Option 4", value: "option-4" },
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
