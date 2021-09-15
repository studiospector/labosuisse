const nunjucks = require('nunjucks')
const plugins = require('../utils/gulp-plugins')
const paths = require('../../config/paths')

const viewPath = paths.src.views

module.exports = function compileNunjucks(templateData, translator, lang) {
  const env = new nunjucks.Environment(new nunjucks.FileSystemLoader(viewPath))

  if (lang) {
    env.addGlobal('__', function(key) {
      translator.setLocale.bind(translator, lang)()
      return translator.__(key)
    })
  }

  env.addFilter('extendDefault', function(obj, defaultObj) {
    Object.keys(defaultObj).forEach(key => {
      if (!obj[key]) obj[key] = defaultObj[key]
    })
    return obj
  })

  return plugins.nunjucks.compile(templateData, {
    env: env
  })
}
