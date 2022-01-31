import { gsap } from 'gsap';
import { qs } from '@okiba/dom';

export const spanTimeline = (span, y) => {
    const tl = gsap.timeline();
    tl.fromTo(span, { translateY: 0, rotate: 0 }, { translateY: y, rotate: 0 });
    tl.to(span, { translateY: y, rotate: 45 * (Math.abs(y) / y) });
    return tl;
}

export const hamburgerTimeline = (el) => {
    const top = `${el}>span:first-child`
    const bottom = `${el}>span:last-child`
    const tl = gsap.timeline();
    tl.add(spanTimeline(top, 1), 0)
    tl.add(spanTimeline(bottom, -7), 0);
    return tl;
}



export const menuTimeline = (menu) => {
    const tl = gsap.timeline();
    tl.fromTo(menu, { xPercent: () => menu.closest('[dir="rtl"]') ? 100 : -100, opacity: 0 }, { xPercent: 0, opacity: 1 })
    return tl;
}

export const itemsTimeline = (items) => {
    const tl = gsap.timeline();
    tl.fromTo(items, { opacity: 0 }, { opacity: 1, stagger: .05, duration: .2 })
    return tl;
}

export const searchTimeline = (el) => {
    const tl = gsap.timeline();
    tl.fromTo(el, { opacity: 1 }, { opacity: 0, duration: .2 })
    return tl;
}

export const stickyHeader = (header, logo, statusbar, translate = true) => {
    const tl = gsap.timeline();
    if (translate) {
        tl.fromTo(header, { top: 0 }, { top: -64 }, 0)
        tl.fromTo(header, { height: 70 }, { height: 0 }, 0)
        tl.fromTo(header, { paddingBottom: 20 }, { paddingBottom: 0 }, 0)
    }
    return tl;
}

export const openMenu = ({ menuElement, hamburgerElement, headerElement, logoElement, searchElement, items }) => {
    let header = typeof headerElement == 'string' ? qs(headerElement) : headerElement;
    const tl = gsap.timeline({ paused: true });
    tl.call(() => header.classList.remove('lb-header--hover'), 0)
    tl.call(() => header.classList.add('lb-header--hover'))
    tl.add(stickyHeader(qs('.lb-header__wrapper--mobile', header), logoElement).duration(.4), 0);
    tl.add(searchTimeline(searchElement).duration(.4), 0);
    tl.add(hamburgerTimeline(hamburgerElement).duration(.4), 0);
    tl.add(menuTimeline(menuElement).duration(.4), .4);
    // tl.add(itemsTimeline(items), .3)
    return tl;
}


export const stickyHeaderScroll = (header, logo) => {
    const tl = stickyHeader(qs('.lb-header__wrapper--mobile', header), logo);
    return gsap.timeline({
        scrollTrigger: {
            trigger: document.body,
            scroller: '.js-scrollbar',
            once: false,
            scrub: .5,
            start: '5 top',
            end: '6 top',
            invalidateOnRefresh: false,
        }
    })
    .add(
        gsap.timeline()
            // .call(() => header.classList.remove('lb-header--scrolled'), 0)
            .add(tl)
            // .call(() => header.classList.add('lb-header--scrolled'))
    )
}
