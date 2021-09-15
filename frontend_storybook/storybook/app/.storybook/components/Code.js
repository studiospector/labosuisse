import Prism from 'prismjs'
// import pretty from 'pretty'
import beautify from 'js-beautify'
import Component from '@okiba/component'

const ui = {
  copyButton: '.sb-code-copy',
  code: 'code'
}

class Code extends Component {
  constructor({ el, options }) {
    super({ el, ui, options })
    this.onCopyClick = this.onCopyClick.bind(this)

    this.listen()
    this.highlightCode()
  }

  listen() {
    this.ui.copyButton.addEventListener('click', this.onCopyClick)
  }

  onCopyClick(e) {
    e.preventDefault()
    const textarea = document.createElement('textarea')
    textarea.innerHTML = this.prettify(this.options.storyFn())
    document.body.appendChild(textarea)
    textarea.select()

    this.ui.copyButton.innerHTML = 'Copied!'

    setTimeout(() => {
      this.ui.copyButton.innerHTML = 'Copy code'
    }, 1500)

    document.execCommand('copy')
    document.body.removeChild(textarea)
  }

  highlightCode() {
    if (this.ui.code) {
      const html = this.decode(this.ui.code.innerHTML)
      const formattedHtml = this.prettify(html)
      this.ui.code.innerHTML = Prism.highlight(formattedHtml, Prism.languages.html, 'html')
    }
  }

  decode(html) {
    const e = document.createElement('textarea')
    e.innerHTML = html
    // handle case of empty input
    return e.childNodes.length === 0 ? '' : e.childNodes[0].nodeValue
  }

  prettify(html) {
    return beautify.html(html, { indent_size: 2, preserve_newlines: false, space_in_empty_paren: true })
  }
}

export default Code
