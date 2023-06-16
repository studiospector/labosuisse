const device = () => {
  const html = document.querySelector('html')
  // html.className += ' ' + checkIe()
  html.className += ' ' + checkSafari()
  html.className += ' ' + checkPlatform()
  // html.className += ' ' + checkTouch()
  // html.className += ' ' + androidVersion()
}

const checkSafari = () => {
  const ua = window.navigator.userAgent
  if (
    ua.indexOf('Safari') != -1 &&
    ua.indexOf('Chrome') === -1 &&
    ua.indexOf('Mobile') === -1 &&
    ua.indexOf('iPhone') === -1
  ) {
    return 'safari'
  } else {
    return ''
  }
}

const checkIe = () => {
  const ua = window.navigator.userAgent
  let version
  const msie = ua.indexOf('MSIE ')
  const trident = ua.indexOf('Trident/')
  const edge = ua.indexOf('Edge/')

  if (msie > 0) {
    version = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10)
    return 'ie' + version
  }

  if (trident > 0) {
    var rv = ua.indexOf('rv:')
    version = parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10)
    return 'ie' + version
  }

  if (edge > 0) {
    version = parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10)
    return 'edge' + version
  }

  // return 'ie11';
  return 'not-ie'
}

const androidVersion = () => {
  const userAgent = navigator.userAgent || navigator.vendor || window.opera
  const version = 'no-android'

  if (/android/i.test(userAgent)) {
    if (userAgent.indexOf('Android 5.') >= 0) {
      version = 'android-5'
    } else {
      version = 'android-4'
    }

    if (userAgent.indexOf('Chrome') >= 0 && userAgent.indexOf('Safari')) {
      version += ' android-chrome'
    }
  }

  return version
}

const checkPlatform = () => {
  const userAgent = navigator.userAgent || navigator.vendor || window.opera

  // Windows Phone must come first because its UA also contains 'Android'
  if (/windows phone/i.test(userAgent)) {
    return 'Windows Phone'
  }

  if (/android/i.test(userAgent)) {
    return 'Android'
  }

  // iOS detection from: http://stackoverflow.com/a/9039885/177710
  if (/iPad|iPhone|iPod/.test(userAgent) || userAgent.match(/Mac/) && navigator.maxTouchPoints && navigator.maxTouchPoints > 2) {
    return 'iOS'
  }

  return 'no-device'
}

const checkTouch = () => {
  if (
    checkPlatform() === 'no-device' &&
    'ontouchstart' in window
  ) {
    return 'no-touch'
  } else if ('ontouchstart' in window) {
    return 'touch'
  } else {
    return 'no-touch'
  }
}

export default device
