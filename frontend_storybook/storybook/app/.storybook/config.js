import 'prismjs/themes/prism.css'
import '../styles/critical.scss'
import '../styles/main.scss'

import { configure, addParameters, addDecorator } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
import { INITIAL_VIEWPORTS } from '@storybook/addon-viewport'
import { qs } from '@okiba/dom'
import Component from '@okiba/component'

import Code from './components/Code'

// automatically import all files ending in *.stories.js
const req = require.context('../stories', true, /\.stories\.js$/);
function loadStories() {
  req.keys().forEach(filename => req(filename));
}

configure(loadStories, module);

addParameters({
  options: {
    panelPosition: 'bottom',
  },
  viewport: {
    viewports: INITIAL_VIEWPORTS,
    defaultViewport: 'responsive'
  }
})

addDecorator(storyFn => {
  useEffect(() => {
    new Component({
      el: qs('.sb-code-block'),
      components: [
        {
          ghost: true,
          type: Code,
          options: {
            storyFn
          }
        }
      ]
    })
  }, [])

  const section = document.createElement('section')
  section.innerHTML = `
    ${storyFn()}
    <div class="sb-code-block">
      <button class="sb-code-copy">Copy code</button>
      <pre class="language-html"><code>
        ${storyFn()}
      </code></pre>
    </div>
  `
  return section
})