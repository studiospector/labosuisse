const gulp = require('gulp')
const glob = require('globby')
const path = require('path')

// Register all tasks
glob.sync('./tasks/*.js').forEach(taskFile => {
  const name = path.basename(taskFile, '.js')
  const task = require(taskFile)
  gulp.task(name, task)
})


gulp.task('dev',
  gulp.series(
    'clean',
    gulp.parallel(
      'styles',
      'webpack-scripts-es5',
      'webpack-scripts-modern',
      'webpack-independent-es5',
      'webpack-independent-modern',
    ),
    'health',
    'watch'
  )
)

gulp.task('build',
  gulp.series(
    'clean',
    gulp.parallel(
      'styles',
      'webpack-scripts-es5',
      'webpack-scripts-modern',
      'webpack-independent-es5',
      'webpack-independent-modern',
    ),
    'rev'
  )
)
