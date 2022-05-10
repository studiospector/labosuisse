import Component from '@okiba/component'

import axiosClient from '../HTTPClient'

import autoComplete from '@tarekraafat/autocomplete.js'
import { on, qs } from '@okiba/dom'

const ui = {
    input: '.lb-search-autocomplete__input',
}

class SearchAutocomplete extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.labelResLess = this.el.dataset.labelResLess
        this.labelResMore = this.el.dataset.labelResMore

        this.init()
    }

    init = () => {
        const autoCompleteJS = new autoComplete({
            selector: () => {
                return this.ui.input
            },
            wrapper: false,
            data: {
                // keys: ["food", "cities", "animals"],
                cache: true,
                src: async () => {
                    try {
                        const { data } = await axiosClient.get('/wp-json/v1/global-search/autocomplete')
                        return data
                    } catch (error) {
                        return error
                    }
                },
                filter: (list) => {
                    // // Filter duplicates
                    // const filteredResults = Array.from(
                    //     new Set(list.map((value) => value.match))
                    // ).map((food) => {
                    //     return list.find((value) => value.match === food);
                    // });

                    return list
                },
            },
            // placeHolder: "",
            resultsList: {
                noResults: true,
                maxResults: 100,
                tabSelect: true,
                destination: () => {
                    return this.ui.input
                },
                class: 'lb-search-autocomplete__selection',
                element: (list, data) => {
                    const info = document.createElement("p")
                    info.classList = 'lb-search-autocomplete__selection__results'
                    if (data.results.length == 0 || data.results.length > 1) {
                        info.innerHTML = `<strong>${data.matches.length}</strong> ${this.labelResMore} <strong>"${data.query}"</strong>`
                    } else {
                        info.innerHTML = `<strong>${data.matches.length}</strong> ${this.labelResLess} <strong>"${data.query}"</strong>`
                    }
                    list.prepend(info)
                },
            },
            resultItem: {
                highlight: true,
                class: 'lb-search-autocomplete__selection__item',
                element: (item, data) => {
                    item.innerHTML = `
                        <span class="lb-search-autocomplete__selection__item__text">
                            ${data.match}
                        </span>
                    `
                },
            },
            events: {
                input: {
                    focus: () => {
                        if (autoCompleteJS.input.value.length) autoCompleteJS.start()
                    }
                }
            }
        })


        autoCompleteJS.input.addEventListener("selection", (event) => {
            const feedback = event.detail
            // const selection = feedback.selection.value[feedback.selection.key]
            const selection = feedback.selection.value
            autoCompleteJS.input.value = selection
        })


        on(this.ui.input, 'init', this.manageScrollbar)
    }

    manageScrollbar = () => {
        const elementsToDisable = qs('.lb-search-autocomplete__selection')
        on(elementsToDisable, 'mouseenter', this.disableLocomotive)
        on(elementsToDisable, 'mouseleave', this.enableLocomotive)
    }

    disableLocomotive = (ev) => {
        window.getCustomScrollbar.stop()
    }

    enableLocomotive = (ev) => {
        window.getCustomScrollbar.start()
    }
}

export default SearchAutocomplete
