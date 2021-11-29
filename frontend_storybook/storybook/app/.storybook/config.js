// PrismJS Style
import 'prismjs/themes/prism.css'

// Storybook Style
import '../styles/storybook.scss'

// Storybook API
import { configure, addParameters, addDecorator } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
import { INITIAL_VIEWPORTS } from '@storybook/addon-viewport'

// Okiba API
import { qs } from '@okiba/dom'
import Component from '@okiba/component'

// Theme Symbols
import renderSymbols from '../views/components/symbols.twig'

// Lazysizes
import lazySizes from 'lazysizes'

// Components Code Block
import Code from './components/Code'



// Automatically import all files ending in *.stories.js
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
    const div = document.createElement('div')
    const symbols = renderSymbols()
    div.innerHTML = symbols
    document.body.appendChild(div)
    return storyFn()
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

// add simple support for lazyload background images
document.addEventListener('lazybeforeunveil', function (e) {
    var bg = e.target.getAttribute('data-bg')
    if (bg) {
        e.target.style.backgroundImage = 'url(' + bg + ')'
    }
})
