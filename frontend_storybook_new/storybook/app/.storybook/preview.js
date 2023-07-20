// Storybook Style
import '../styles/storybook.scss'

// Lazysizes
import lazySizes from 'lazysizes'
import 'lazysizes/plugins/unveilhooks/ls.unveilhooks'

// Theme Symbols
import renderSymbols from '../views/components/symbols.twig'

/** @type { import('@storybook/html').Preview } */
const preview = {
  parameters: {
    actions: { argTypesRegex: "^on[A-Z].*" },
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/,
      },
    },
  },
  decorators: [
    (Story) => {
      lazySizes.cfg.init = false
      // lazySizes.cfg.loadMode = 3
      lazySizes.cfg.expand = 1
      lazySizes.cfg.expFactor = 0.001
      lazySizes.cfg.hFac = 0.001
    
      lazySizes.init()

      const symbols = renderSymbols()
      return `
        <div>
          ${symbols}
          ${Story().outerHTML || Story()}
        </div>
      `
    },
  ],
};

export default preview;
