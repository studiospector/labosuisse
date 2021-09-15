const gulp = require('gulp')
const path = require('path')
const fetch = require('node-fetch')
const paths = require('../config/paths')
const plugins = require('./utils/gulp-plugins')
const lazypipe = require('lazypipe')
const autoprefixer = require('autoprefixer')
const Golc = require('golc')
const L = new Golc('frontend_client | styles')
const { production } = require('../config/options')

function getOptimizePipe() {
  let destPath = paths.dist.styles
  if (production) {
    destPath = path.normalize(destPath.replace(paths.dist.root, '/var/tmp/'))
  }

  return lazypipe()
    .pipe(() => gulp.dest(destPath))
    .pipe(plugins.cleanCss, {
      advanced: false,
      aggressiveMerging: false,
      mediaMerging: false,
      rebase: false
    })()
}

module.exports = function() {
  function errorNotifier(error) {
    L.error(error.messageFormatted)
  }

  const pipeline = gulp.src(`${paths.src.styles}/*.{sass,scss}`)
    .pipe(plugins.if(!production, plugins.plumber({errorHandler: errorNotifier})))
    .pipe(plugins.if(!production, plugins.sourcemaps.init()))
    .pipe(plugins.sass({
      precision: 10,
      outputStyle: 'expanded',
    }).on('error', plugins.sass.logError))
    .pipe(plugins.postcss([
      autoprefixer()
    ]))
    .pipe(plugins.if(production, getOptimizePipe()))
    .pipe(plugins.if(!production, plugins.sourcemaps.write('.')))
    .pipe(gulp.dest(paths.dist.styles + '/'))
    .pipe(plugins.size({ title: 'styles' }))

  if (!production) {
    pipeline.on('end', _ => {
      fetch('http://frontend_browser-sync/__browser_sync__?method=reload&args=*.css')
        .then(_ => L.info('Styles refreshed'))
        .catch(_ => L.error('Can\'t refresh styles'))
    })
  }

  return pipeline
}
