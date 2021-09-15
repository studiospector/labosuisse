const toArray = Array.prototype.slice
const loadToStart = toArray.call(document.querySelectorAll('[data-deferStart]'))
const scriptSrcData = 'noModule' in HTMLScriptElement.prototype ? 'data-modernsrc' : 'data-src'
let started = false

function onLoadScripts(e) {
  e.currentTarget.setAttribute('data-loaded', true)
  if (started) return
  let canStart = true
  for (let i = 0; i < loadToStart.length; i++) {
    if(!loadToStart[i].getAttribute('data-loaded')){
      canStart = false
    }
  }

  if (canStart) {
    started = true
    window.APP.init()
  }
}

function loadScripts() {
  const appScripts = toArray.call(document.querySelectorAll(`script[${scriptSrcData}]`))
  const appStyles = toArray.call(document.querySelectorAll('link[data-href]'))
  for (let i = 0; i < appScripts.length; i++) {
    const script = appScripts[i];
    script.addEventListener('load', onLoadScripts)
    script.setAttribute('src', script.getAttribute(scriptSrcData))
  }

  for (let i = 0; i < appStyles.length; i++) {
    const style = appStyles[i];
    style.addEventListener('load', onLoadScripts)
    style.setAttribute('href', style.getAttribute('data-href'))
  }
}

function init() {
  document.fonts
    ? document.fonts.ready.then(loadScripts)
    : loadScripts()
}

document.addEventListener('DOMContentLoaded', init)

