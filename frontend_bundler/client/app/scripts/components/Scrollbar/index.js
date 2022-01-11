import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import LocomotiveScroll from 'locomotive-scroll'

gsap.registerPlugin(ScrollTrigger);

export default class Scrollbar extends Component {
    constructor({ options, ...props }) {
        super({ ...props })

        const defaultOptions = {
            el: this.el,
            smooth: true,
            // getSpeed: true,
            getDirection: true,
            // reloadOnContextChange: true
        }

        // Init Locomotive Scroll
        const scrollbar = new LocomotiveScroll(defaultOptions)

        // each time Locomotive Scroll updates, tell ScrollTrigger to update too (sync positioning)
        scrollbar.on("scroll", ScrollTrigger.update);

        // tell ScrollTrigger to use these proxy methods for the ".js-scrollbar" element since Locomotive Scroll is hijacking things
        ScrollTrigger.scrollerProxy(this.el, {
            scrollTop(value) {
                return arguments.length ? scrollbar.scrollTo(value, 0, 0) : scrollbar.scroll.instance.scroll.y;
            }, // we don't have to define a scrollLeft because we're only scrolling vertically.
            getBoundingClientRect() {
                return {top: 0, left: 0, width: window.innerWidth, height: window.innerHeight};
            },
            // LocomotiveScroll handles things completely differently on mobile devices - it doesn't even transform the container at all! So to get the correct behavior and avoid jitters, we should pin things with position: fixed on mobile. We sense it by checking to see if there's a transform applied to the container (the LocomotiveScroll-controlled element).
            pinType: document.querySelector(".js-scrollbar").style.transform ? "transform" : "fixed"
        });

        // Add Locomotive Scroll to window for global access
        window.getCustomScrollbar = scrollbar
    }


}