import Application from '~/components/app'

function init() {
  console.log('initAPP')
  return new Application()
}

if (!window.APP) window.APP = {}
window.APP.init = init
