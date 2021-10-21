import { storiesOf } from '@storybook/html'
import renderButton from '../../views/components/button.twig'

storiesOf('UIKit|Buttons', module)
  .add('Primary', () => renderButton({ label: 'Lorem ipsum', variants: ['primary'] }))
  .add('Primary Disabled', () => renderButton({ label: 'Lorem ipsum', variants: ['primary'], attributes: ['disabled'] }))
  .add('Secondary', () => renderButton({ label: 'Lorem ipsum', variants: ['secondary'] }))
  .add('Secondary Disabled', () => renderButton({ label: 'Lorem ipsum', variants: ['secondary'], attributes: ['disabled'] }))
  .add('Tertiary', () => renderButton({ label: 'Lorem ipsum', variants: ['tertiary'] }))
  .add('Tertiary Disabled', () => renderButton({ label: 'Lorem ipsum', variants: ['tertiary'], attributes: ['disabled'] }))

  // .add('Negative', () => renderButton({ label: 'Lorem ipsum', variants: ['negative'] }))
  // .add('Dark', () => renderButton({ label: 'Lorem ipsum', variants: ['dark'] }))
  // .add('Outline', () => renderButton({ label: 'Lorem ipsum', variants: ['outline'] }))
  // .add('Small', () => renderButton({ label: 'Lorem ipsum', variants: ['small'] }))
  // .add('Only icon', () => renderButton({ variants: ['iconOnly'], label: 'Lorem ipsum', iconStart: { name: "edit" } }))
  // .add('With icon (start)', () => renderButton({ label: 'Lorem ipsum', iconStart: { name: "edit" } }))
  // .add('With icon (end)', () => renderButton({ label: 'Lorem ipsum', iconEnd: { name: "chevron-right" } }))
  // .add('Loading', () => renderButton({ label: 'Lorem ipsum', variants: ['loading'] }))
