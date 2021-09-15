const paths = require('../config/paths')
const del = require('del')

module.exports = () => {
  return Promise.all([
    del(`${paths.dist.public}/**/*.*`, { dot: true, allowEmpty: false }),
  ])
}
