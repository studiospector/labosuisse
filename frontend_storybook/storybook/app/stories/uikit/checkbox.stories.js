// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderCheckbox from '../../views/components/fields/checkbox.twig'

// Vertical
const dataVertical = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
        { label: 'Option 1', name: 'option-1', value: 1 },
        { label: 'Option 2', name: 'option-2', value: 2 },
        { label: 'Option 3', name: 'option-3', value: 3 },
        { label: 'Option 4', name: 'option-4', value: 3 },
    ],
    variants: ['vertical'],
}

// Horizontal
const dataHorizontal = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
        { label: 'Option 1', name: 'option-1', value: 1 },
        { label: 'Option 2', name: 'option-2', value: 2 },
        { label: 'Option 3', name: 'option-3', value: 3 },
        { label: 'Option 4', name: 'option-4', value: 3 },
    ],
    variants: ['horizontal'],
}

// Disabled
const dataDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
        { label: 'Option 1', name: 'option-1', value: 1 },
        { label: 'Option 2', name: 'option-2', value: 2, disabled: true },
        { label: 'Option 3', name: 'option-3', value: 3, disabled: true },
        { label: 'Option 4', name: 'option-4', value: 3 },
    ],
    variants: ['vertical'],
}

storiesOf('UIKit|Checkbox', module)
    // Vertical
    .add('Vertical', () => renderCheckbox(dataVertical))
    // Horizontal
    .add('Horizontal', () => renderCheckbox(dataHorizontal))
    // Disabled
    .add('Disabled', () => renderCheckbox(dataDisabled))
