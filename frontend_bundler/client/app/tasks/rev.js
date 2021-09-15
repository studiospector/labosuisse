const gulp = require('gulp')
const plugins = require('./utils/gulp-plugins')
const paths = require('../config/paths')

const stylesPath = paths.dist.styles
const scriptsPath = paths.dist.scripts

module.exports = () => gulp.src(
  [
    `${stylesPath}/*.*`,
    `${scriptsPath}/*.*`,
  ], {base: paths.dist.public})
  .pipe(plugins.rev())
  .pipe(gulp.dest(paths.dist.public + '/'))
  .pipe(plugins.rev.manifest())
  .pipe(gulp.dest(paths.dist.public + '/'))
