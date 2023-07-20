import '../styles/main.scss'

import lazysizes from 'lazysizes'
import 'lazysizes/plugins/unveilhooks/ls.unveilhooks'

import Application from './components/app'
import device from './utils/device'

function loadScripts() {
  lazySizes.cfg.init = false
  // lazySizes.cfg.loadMode = 3
  lazySizes.cfg.expand = 1
  lazySizes.cfg.expFactor = 0.001
  lazySizes.cfg.hFac = 0.001

  lazySizes.init()

  device()
  
  new Application()
}

function init() {
  document.fonts ? document.fonts.ready.then(loadScripts) : loadScripts()
}
document.addEventListener('DOMContentLoaded', init)
