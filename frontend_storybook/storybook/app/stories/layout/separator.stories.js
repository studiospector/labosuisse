// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderSeparator from '../../views/components/separator.twig'

storiesOf('Layout|Separator', module)
    // Big
    .add('Big', () => renderSeparator({ variants: ['big'] }))
    // Medium
    .add('Medium', () => renderSeparator({ variants: ['medium'] }))
    // Small
    .add('Small', () => renderSeparator({ variants: ['small'] }))
