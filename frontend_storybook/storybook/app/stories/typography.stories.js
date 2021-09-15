import { document } from 'global'
import { storiesOf } from '@storybook/html'
import { appendChildren } from './utils'

storiesOf('Base|Typography', module)
  .add('Titles', () => {
    const div = document.createElement('div')
    appendChildren('<h1>Title</h1>', div)
    return div.innerHTML
  })
