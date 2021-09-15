const { templateEngine } = require('../../config/options')
const compileTwig = require('./views-twig')
const compileNjk = require('./views-njk')


let compileViews

switch (templateEngine) {
  case 'njk':
    compileViews = compileNjk
    break
  default:
    compileViews = compileTwig
    break
}

module.exports = compileViews


