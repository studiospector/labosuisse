const map = require('map-stream')
const rext = require('replace-ext')
const Golc = require('golc')
const L = new Golc('frontend_client | twig', {withNewline: true})

const PROCESS_NAME = 'twig'

module.exports = function(options) {
  options = Object.assign({}, {
    changeExt: true,
    extname: '.html',
    useFileContents: false,

  }, options || {})

  function modifyContents(file, cb) {
    const data = file.data || Object.assign({}, options.data)

    if (file.isNull()) {
      return cb(null, file)
    }

    if (file.isStream()) {
      return cb(L.error('Streaming not supported!'))
    }

    data._file   = file
    if (options.changeExt === false || options.extname === true) {
      data._target = {
        path: file.path,
        relative: file.relative
      }
    } else {
      data._target = {
        path: rext(file.path, options.extname || ''),
        relative: rext(file.relative, options.extname || '')
      }
    }

    const Twig = require('twig')
    const twig = Twig.twig
    const twigOpts = {
      path: file.path,
      async: false
    }
    let template

    if (options.debug !== undefined) {
      twigOpts.debug = options.debug
    }
    if (options.trace !== undefined) {
      twigOpts.trace = options.trace
    }
    if (options.base !== undefined) {
      twigOpts.base = options.base
    }
    if (options.namespaces !== undefined) {
      twigOpts.namespaces = options.namespaces
    }
    if (options.cache !== true) {
      Twig.cache(false)
    }

    if (options.functions) {
      options.functions.forEach(function(func) {
        Twig.extendFunction(func.name, func.func)
      })
    }

    if (options.filters) {
      options.filters.forEach(function(filter) {
        Twig.extendFilter(filter.name, filter.func)
      })
    }

    if (options.extend) {
      Twig.extend(options.extend)
      delete options.extend
    }

    if (options.useFileContents) {
      const fileContents = file.contents.toString()
      twigOpts.data = fileContents
    }

    template = twig(twigOpts)

    try {
      file.contents = new Buffer(template.render(data))
    } catch (e) {
      if (options.errorLogToConsole) {
        L.info(PROCESS_NAME + ' ' + e)
        return cb()
      }

      if (typeof options.onError === 'function') {
        options.onError(e)
        return cb()
      }
      return cb(L.error(PROCESS_NAME, e))
    }

    file.path = data._target.path
    cb(null, file)
  }

  return map(modifyContents)
}
