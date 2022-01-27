import Application from '~/components/app'

import device from './utils/device'

device()

function init() {
  console.log('initAPP')
  return new Application()
}

if (!window.APP) window.APP = {}
window.APP.init = init
