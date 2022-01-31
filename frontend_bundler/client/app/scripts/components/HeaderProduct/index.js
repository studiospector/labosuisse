import Component from '@okiba/component'
import { qsa, qs, on, off } from '@okiba/dom'

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export default class HeaderProduct extends Component {
    constructor({ options, ...props }) {
        super({ ...props })

        this.elemHeight = this.el.getBoundingClientRect().height

        // const locoScroll = window.getCustomScrollbar

        // // each time Locomotive Scroll updates, tell ScrollTrigger to update too (sync positioning)
        // locoScroll.on("scroll", ScrollTrigger.update);

        // // tell ScrollTrigger to use these proxy methods for the ".js-scrollbar" element since Locomotive Scroll is hijacking things
        // ScrollTrigger.scrollerProxy(".js-scrollbar", {
        //     scrollTop(value) {
        //         return arguments.length ? locoScroll.scrollTo(value, 0, 0) : locoScroll.scroll.instance.scroll.y;
        //     }, // we don't have to define a scrollLeft because we're only scrolling vertically.
        //     getBoundingClientRect() {
        //         return { top: 0, left: 0, width: window.innerWidth, height: window.innerHeight };
        //     },
        //     // LocomotiveScroll handles things completely differently on mobile devices - it doesn't even transform the container at all! So to get the correct behavior and avoid jitters, we should pin things with position: fixed on mobile. We sense it by checking to see if there's a transform applied to the container (the LocomotiveScroll-controlled element).
        //     pinType: document.querySelector(".js-scrollbar").style.transform ? "transform" : "fixed"
        // });



        

        const observer = new MutationObserver( (event) => {
            let classes = event[0].target.classList
            setTimeout(() => {
                // if (classes.contains('lb-header--hide')) {
                    console.log('+++', classes.contains('lb-header--hide'))
                    this.adjustElement(0, false, classes.contains('lb-header--hide'))
                // }
            }, 300);
        })
        observer.observe(qs('.lb-header'), {
            attributes: true, 
            attributeFilter: ['class'],
            childList: false, 
            characterData: false
        })

        this.aaa()



        // // each time the window updates, we should refresh ScrollTrigger and then update LocomotiveScroll. 
        // ScrollTrigger.addEventListener("refresh", () => locoScroll.update());

        // // after everything is set up, refresh() ScrollTrigger and update LocomotiveScroll because padding may have been added for pinning, etc.
        // ScrollTrigger.refresh();



        // this.adjustElement(this.elemHeight)
        // on(window, 'resize', this.adjustElement(this.elemHeight))
    }

    aaa = () => {
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: ".js-single-product-details",
                scroller: ".js-scrollbar",
                start: "top top",
                // end: "+=100%",
                onEnter: () => {
                    this.adjustElement(0, true)
                    this.el.classList.add('lb-header-sticky-product--scrolled')
                },
                // onLeave: () => this.el.classList.add('lb-header-sticky-product--scrolled'),
                onEnterBack: () => {
                    setTimeout(() => {
                        this.adjustElement(qs('.lb-header').getBoundingClientRect().height, true)
                        this.el.classList.remove('lb-header-sticky-product--scrolled')
                    }, 300);
                },
                // onLeaveBack: () => this.el.classList.remove('lb-header-sticky-product--scrolled'),
                onUpdate() {
                    console.log("Update")
                }
            }
        })

        // tl.fromTo(this.el, { backgroundColor: "#000" }, { backgroundColor: "#28a92b" })
    } 

    adjustElement = (elemHeight, aaa, bbb) => {
        console.log('_______', aaa, bbb);
        if (aaa || (bbb === undefined || bbb === false)) {
            console.log('');
            console.log('---');
            const headerHeight = qs('.lb-header').getBoundingClientRect().height - (elemHeight ? elemHeight : 0)
            console.log(qs('.lb-header').getBoundingClientRect().height, elemHeight, headerHeight)
            this.el.style.top = `${headerHeight}px`
            console.log('---');
            console.log('');
        }
    }
}
