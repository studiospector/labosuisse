export const allowTouchMove = el => {
  while (el && el !== document.body) {
    if (el.getAttribute('body-scroll-lock-ignore') !== null) {
      return true;
    }
    el = el.parentElement;
  }
}
