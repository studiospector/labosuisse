import Component from '@okiba/component'

import axiosClient from '../HTTPClient'

import autoComplete from '@tarekraafat/autocomplete.js'

const ui = {
    input: '.lb-search-autocomplete__input',
}

class SearchAutocomplete extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.init()
    }

    init = () => {
        const autoCompleteJS = new autoComplete({
            selector: '.lb-search-autocomplete__input',
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
            placeHolder: "Search for Food & Drinks!",
            resultsList: {
                noResults: true,
                maxResults: -1,
                tabSelect: true,
                destination: ".lb-search-autocomplete__input",
                class: 'lb-search-autocomplete__selection',
                element: (list, data) => {
                    const info = document.createElement("p")
                    info.classList = 'lb-search-autocomplete__selection__results'
                    if (data.results.length > 0) {
                        info.innerHTML = `<strong>${data.matches.length}</strong> results`
                    } else {
                        info.innerHTML = `Found <strong>${data.matches.length}</strong> matching results for <strong>"${data.query}"</strong>`
                    }
                    list.prepend(info)
                },
            },
            resultItem: {
                highlight: true,
                class: 'lb-search-autocomplete__selection__item',
                element: (item, data) => {
                    // Modify Results Item Content
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
            // Prepare User's Selected Value
            // const selection = feedback.selection.value[feedback.selection.key]
            const selection = feedback.selection.value
            // Replace Input value with the selected value
            autoCompleteJS.input.value = selection
            // Console log autoComplete data feedback
            // console.log(feedback)
        })
    }
}

export default SearchAutocomplete
