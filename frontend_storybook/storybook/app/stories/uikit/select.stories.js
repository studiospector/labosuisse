// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderSelect from '../../views/components/fields/select.twig'

import LBCustomSelect from '../../scripts/components/CustomSelect'

// Default
const dataDefault = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: false,
    required: false,
    disabled: false,
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['default']
}

// Default Multiple
const dataDefaultMultiple = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: true,
    required: false,
    disabled: false,
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['default']
}

// Default Disabled
const dataDefaultDisabled = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: false,
    required: false,
    disabled: true,
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['default']
}

// Primary
const dataPrimary = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: false,
    required: false,
    disabled: false,
    confirmBtnLabel: 'Applica',
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['primary']
}

// Primary Multiple
const dataPrimaryMultiple = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: true,
    required: false,
    disabled: false,
    confirmBtnLabel: 'Applica',
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['primary']
}

// Primary Disabled
const dataPrimaryDisabled = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: true,
    required: false,
    disabled: true,
    confirmBtnLabel: 'Applica',
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['primary']
}

// Secondary
const dataSecondary = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: false,
    required: false,
    disabled: false,
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['secondary']
}

// Secondary Multiple
const dataSecondaryMultiple = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: true,
    required: false,
    disabled: false,
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['secondary']
}

// Secondary Disabled
const dataSecondaryDisabled = {
    id: 'test-select',
    label: 'Test label',
    placeholder: 'Test placeholder',
    multiple: true,
    required: false,
    disabled: true,
    options: [
        {
            value: 'aaa',
            label: 'AAA',
        },
        {
            value: 'bbb',
            label: 'BBB',
        },
        {
            value: 'ccc',
            label: 'CCC',
        },
    ],
    variants: ['secondary']
}

storiesOf('UIKit|Select', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-custom-select',
                        type: LBCustomSelect
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    // Default
    .add('Default', () => renderSelect(dataDefault))
    // Default Multiple
    .add('Default --- Multiple', () => renderSelect(dataDefaultMultiple))
    // Default Disabled
    .add('Default --- Disabled', () => renderSelect(dataDefaultDisabled))
    // Primary
    .add('Primary', () => renderSelect(dataPrimary))
    // Primary Multiple
    .add('Primary --- Multiple', () => renderSelect(dataPrimaryMultiple))
    // Primary Multiple
    .add('Primary --- Disabled', () => renderSelect(dataPrimaryDisabled))
    // Secondary
    .add('Secondary', () => renderSelect(dataSecondary))
    // Secondary Multiple
    .add('Secondary --- Multiple', () => renderSelect(dataSecondaryMultiple))
    // Secondary Multiple
    .add('Secondary --- Disabled', () => renderSelect(dataSecondaryDisabled))
