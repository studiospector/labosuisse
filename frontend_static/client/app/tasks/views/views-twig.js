const twig = require('../utils/compile-twig')
const paths = require('../../config/paths')
const viewPath = paths.src.views

module.exports = function compileTwig(templateData, translator, lang) {
  const functions = []

  if (lang) {
    functions.push({
      name: '__',
      func: function(key) {
        translator.setLocale.bind(translator, lang)()
        return translator.__(key)
      }
    })
  }

  const filters = []

  return twig({
    data: templateData,
    base: viewPath,
    functions,
    filters,
  })
}
