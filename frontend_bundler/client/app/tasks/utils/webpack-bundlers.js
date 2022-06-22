const glob = require('glob')
const webpack = require('webpack')
const path = require('path')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin

const paths = require('../../config/paths')
const options = require('../../config/options')
const webpackConfigEs5 = require('../../config/webpack/es5.js')
const webpackConfigModern = require('../../config/webpack/modern.js')

const srcPath = path.resolve(paths.src.scripts)
const entries = {}
const independentEntries = {}
const plugins = []

function getAnalizer(key) {
  return new BundleAnalyzerPlugin({
    analyzerMode: 'json',
    statsFilename: `stat.${key}.json`,
    generateStatsFile: true,
    statsOptions: { source: false }
  })
}

glob.sync(`${srcPath}/*.js`).forEach((filepath) => {
  const entryId = path.basename(filepath, '.js')
  const entry = []

  if (!options.production) {
    entry.push('webpack/hot/dev-server')
    entry.push('webpack-hot-middleware/client?reload=true')
  }

  // Actual entry MUST be after webpack stuff
  entry.push(filepath)

  entries[entryId] = entry
})

glob.sync(`${srcPath}/independent/**/*.js`).forEach((filepath) => {
  const entryId = path.basename(filepath, '.js')
  independentEntries[entryId] = filepath
})

if (!options.production) {
  plugins.push(new webpack.HotModuleReplacementPlugin())
}

plugins.push(new webpack.DefinePlugin({
  'process.env': {
    'LB_API_URL': JSON.stringify(process.env.LB_API_URL),
    'LB_API_TOKEN': JSON.stringify(process.env.LB_API_TOKEN)
  }
}))

const bundlers = {
  scripts: webpack(Object.assign({}, webpackConfigEs5, {
    entry: entries,
    plugins: [...plugins, getAnalizer('scripts')]
  })),
  scriptsModern: webpack(Object.assign({}, webpackConfigModern, {
    entry: entries,
    plugins: [...plugins, getAnalizer('modern')]
  }))
}

if (Object.keys(independentEntries).length) {
  const destPath = paths.dist.scripts + '/independent'
  const outputEs5 = Object.assign({}, webpackConfigEs5.output, {path: path.join(process.cwd(), destPath)})
  const outputModern = Object.assign({}, webpackConfigModern.output, {path: path.join(process.cwd(), destPath)})

  bundlers.independent = webpack(Object.assign({}, webpackConfigEs5, {entry: independentEntries, plugins: [], output: outputEs5}))
  bundlers.independentModern = webpack(Object.assign({}, webpackConfigModern, {entry: independentEntries, plugins: [], output: outputModern}))
}

module.exports = bundlers
