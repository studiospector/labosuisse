const cloneDeep = require('lodash.clonedeep')
const moduleBase = require('./base')
const es5 = cloneDeep(moduleBase)
es5.rules.push(
  {
    test: /\.js$/,
    exclude: [/node_modules\/(?!(swiper|dom7|okiba|@okiba)\/).*/],
    use: { loader: 'babel-loader' },
  }
)
module.exports = es5
