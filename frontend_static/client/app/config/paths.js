const root = '.'
const dist = `${root}/dist`
const pub = `${dist}/public`
const bundle = `${dist}/bundle`
const artifacts = `${dist}/artifacts`

module.exports = {
  root,
  src: {
    styles: `${root}/styles`,
    static: `${root}/static`,
    scripts: `${root}/scripts`,
    assets: `${root}/assets/original`,
    fixtures: `${root}/fixtures`,
    locales: `${root}/locales`,
    views: `${root}/views`,
  },

  dist: {
    root: dist,
    public: pub,
    artifacts: artifacts,
    bundle: bundle,
    releases: `${artifacts}/releases`,
    styles: `${bundle}/css`,
    scripts: `${bundle}/js`,
    static: `${pub}/static`,
    assets: `${root}/assets/optimized`,
    manifest: `${bundle}/rev-manifest.json`,
    locales: `${artifacts}/locales`,
  }
}
