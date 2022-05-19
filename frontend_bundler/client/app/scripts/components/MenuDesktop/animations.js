import { gsap } from 'gsap';
import breakpoints from '../../utils/breakpoints';
import { qs } from '@okiba/dom';


export const wrapperTimeline = (el) => {
    const tl = gsap.timeline()
        .fromTo(el, { opacity: 0, y: -10 }, { opacity: 1, y: 0 });
    return tl;
}


const backgroundHeight = () => {
    // return window.innerWidth > breakpoints.xl ? '522px' : '395px';
    return window.innerWidth > breakpoints.xl ? '100%' : '100%';
}

export const backgroundTimeline = (el) => {
    const tl = gsap.timeline()
        .fromTo(el, { height: 0, opacity: 0 }, ({ height: 530, opacity: 1, onComplete: () => tl.invalidate() }));
    return tl;
}

export const submenuTimeline = (el) => {
    return gsap.timeline()
        .fromTo(el, { visibility: 'hidden' }, { visibility: 'visible' })
}

export const overlayTimeline = (el) => {
    return gsap.timeline()
        .fromTo(el, { opacity: 0, visibility: 'hidden' }, { opacity: .8, visibility: 'visible' })
}


export const openBackgroundTimeline = (background, overlay) => {
    return gsap.timeline()
        .add(backgroundTimeline(background), 0)
        .add(overlayTimeline(overlay), 0)
}

export const openSubmenuTimeline = (submenu, wrapper) => {
    const header = qs('.lb-header')
    return gsap.timeline()
        .call(() => header.classList.remove('lb-header--hover'), 0)
        .call(() => header.classList.add('lb-header--hover'))
        .add(submenuTimeline(submenu), 0)
        .add(wrapperTimeline(wrapper), 0)
}
