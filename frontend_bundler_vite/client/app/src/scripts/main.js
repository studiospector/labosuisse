import '../styles/main.scss'

import lazysizes from 'lazysizes'
import Application from './components/app'
import device from './utils/device'

console.log(import.meta.env)

function loadScripts() {
  lazySizes.cfg.init = false
  // lazySizes.cfg.loadMode = 3
  lazySizes.cfg.expand = 1
  lazySizes.cfg.expFactor = 0.001
  lazySizes.cfg.hFac = 0.001

  device()

  lazySizes.init()
  
  new Application()
}

function init() {
  document.fonts ? document.fonts.ready.then(loadScripts) : loadScripts()
}
document.addEventListener('DOMContentLoaded', init)
