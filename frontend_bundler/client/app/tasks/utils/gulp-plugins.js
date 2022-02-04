const plugins = require('gulp-load-plugins')({
    postRequireTransforms: {
        'sass': function(sass) {
          return sass(require('sass'))
        }
    }
})
module.exports = plugins
