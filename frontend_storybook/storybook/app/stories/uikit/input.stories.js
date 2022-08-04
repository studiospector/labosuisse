// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderInput from '../../views/components/fields/input.twig'

import LBCustomInput from '../../scripts/components/CustomInput'

// Default
const dataDefault = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: false,
    required: false,
    variants: ['default'],
}

// Default Disabled
const dataDefaultDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: true,
    required: false,
    variants: ['default'],
}

// Primary
const dataPrimary = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: false,
    required: false,
    variants: ['primary'],
}

// Primary Disabled
const dataPrimaryDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: true,
    required: false,
    variants: ['primary'],
}

// Secondary
const dataSecondary = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: false,
    required: false,
    variants: ['secondary'],
}

// Secondary Disabled
const dataSecondaryDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: true,
    required: false,
    variants: ['secondary'],
}

// Tertiary
const dataTertiary = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: false,
    required: false,
    variants: ['tertiary'],
}

// Tertiary Disabled
const dataTertiaryDisabled = {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    disabled: true,
    required: false,
    variants: ['tertiary'],
}

// Email
const dataEmail = {
    type: 'email',
    name: "user-email",
    label: "Email",
    disabled: false,
    required: false,
    variants: ['primary'],
}

// Password
const dataPassword = {
    type: 'password',
    name: "user-password",
    label: "Password",
    disabled: false,
    required: true,
    variants: ['primary'],
}

// Search
const dataSearch = {
    type: 'search',
    name: "search",
    label: "Search",
    disabled: false,
    required: false,
    variants: ['primary'],
}

// Search Secondary
const dataSearchSecondary = {
    type: 'search',
    name: "search",
    label: "Search",
    disabled: false,
    required: false,
    variants: ['secondary'],
}

// Search Tertiary
const dataSearchTertiary = {
    type: 'search',
    name: "search",
    label: "Search",
    disabled: false,
    required: false,
    buttonTypeNext: 'submit',
    buttonVariantNext: 'primary',
    variants: ['tertiary'],
}

// Datepicker
const dataDatepicker = {
    type: 'date',
    name: "date",
    label: "Select date",
    disabled: false,
    required: false,
    variants: ['default'],
}

// Number
const dataNumber = {
    type: 'number',
    name: "num",
    label: null,
    disabled: false,
    required: false,
    step: 1,
    min: 1,
    max: 5,
    value: 1,
    variants: ['secondary'],
}

storiesOf('UIKit|Input', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-custom-input',
                        type: LBCustomInput
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    // Default
    .add('Default', () => renderInput(dataDefault))
    // Default Disabled
    .add('Default --- Disabled', () => renderInput(dataDefaultDisabled))
    // Primary
    .add('Primary', () => renderInput(dataPrimary))
    // Primary Disabled
    .add('Primary --- Disabled', () => renderInput(dataPrimaryDisabled))
    // Secondary
    .add('Secondary', () => renderInput(dataSecondary))
    // Secondary Disabled
    .add('Secondary --- Disabled', () => renderInput(dataSecondaryDisabled))
    // Tertiary
    .add('Tertiary', () => renderInput(dataTertiary))
    // Tertiary Disabled
    .add('Tertiary --- Disabled', () => renderInput(dataTertiaryDisabled))
    // Email
    .add('Email', () => renderInput(dataEmail))
    // Password
    .add('Password', () => renderInput(dataPassword))
    // Number
    .add('Number', () => renderInput(dataNumber))
    // Datepicker
    .add('Datepicker', () => renderInput(dataDatepicker))
    // Search
    .add('Search', () => renderInput(dataSearch))
    // Search Secondary
    .add('Search --- Secondary', () => renderInput(dataSearchSecondary))
    // Search Tertiary
    .add('Search --- Tertiary', () => renderInput(dataSearchTertiary))
