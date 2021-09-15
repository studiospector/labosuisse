const gulp = require('gulp')
const paths = require('../config/paths')

module.exports = function watch() {
  return new Promise(resolve => {
    gulp.watch(`${paths.src.static}/**/*.*`,
      gulp.series('copy-static')
    )

    gulp.watch(`${paths.src.locales}/*.po`,
      gulp.series(
        'convert-po',
        'views',
        'reload-browser'
      )
    )

    gulp.watch(`${paths.src.views}/**/*.{njk,twig,html}`,
      gulp.series(
        'views',
        'reload-browser'
      )
    )
    gulp.watch(`${paths.src.fixtures}/**/*.json`,
      gulp.series(
        'views',
        'reload-browser'
      )
    )

    resolve()
  })
}
