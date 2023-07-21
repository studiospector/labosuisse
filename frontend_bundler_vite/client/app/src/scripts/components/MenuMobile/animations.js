import { gsap } from 'gsap'

const menuTimeline = (menu) => {
  const tl = gsap.timeline()
  tl.fromTo(
    menu,
    { xPercent: () => (menu.closest('[dir="rtl"]') ? 100 : -100), opacity: 0 },
    { xPercent: 0, opacity: 1 }
  )
  return tl
}

const openMenu = ({
  menuElement,
  hamburgerElement,
  headerElement,
  logoElement,
  searchElement,
  items,
}) => {
  const tl = gsap.timeline({ paused: true })
  tl.add(menuTimeline(menuElement).duration(0.4), 0.4)
  return tl
}

export { menuTimeline, openMenu }
