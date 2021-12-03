// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderButton from '../../views/components/button.twig'

storiesOf('UIKit|Buttons', module)
    // Primary
    .add('Primary', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['primary'] }))
    .add('Primary Disabled', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['primary'], attributes: ['disabled'] }))
    .add('Primary with Icon', () => renderButton({ url: '#', title: 'Lorem ipsum', iconEnd: { name: 'arrow-right' }, variants: ['primary'] }))
    // Secondary
    .add('Secondary', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['secondary'] }))
    .add('Secondary Disabled', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['secondary'], attributes: ['disabled'] }))
    .add('Secondary with Icon', () => renderButton({ url: '#', title: 'Lorem ipsum', iconEnd: { name: 'arrow-right' }, variants: ['secondary'] }))
    // Tertiary
    .add('Tertiary', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['tertiary'] }))
    .add('Tertiary Disabled', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['tertiary'], attributes: ['disabled'] }))
    .add('Tertiary with Icon', () => renderButton({ url: '#', title: 'Lorem ipsum', iconEnd: { name: 'arrow-right' }, variants: ['tertiary'] }))
    // Quaternary
    .add('Quaternary', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['quaternary'] }))
    .add('Quaternary Disabled', () => renderButton({ url: '#', title: 'Lorem ipsum', variants: ['quaternary'], attributes: ['disabled'] }))
    .add('Quaternary with Icon', () => renderButton({ url: '#', title: 'Lorem ipsum', iconEnd: { name: 'arrow-right' }, variants: ['quaternary'] }))
