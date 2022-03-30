import Component from '@okiba/component'
import { qs, on } from '@okiba/dom'

import axiosClient from '../HTTPClient'

import DOMPurify from 'dompurify'

const ui = {
    searchForm: '.lb-filters__search-form',
    selects: {
        selector: '.custom-field select',
        asArray: true
    },
    buttons: {
        selector: '.custom-field .custom-select-items__btn-confirm',
        asArray: true
    },
}

class Filters extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.cardsGrid = qs('.js-lb-cards-grid')
        this.pagination = qs('.lb-pagination')
        this.loadMore = qs('.js-load-more')
        this.loadMoreBtn = qs('.button', this.loadMore)

        this.payload = {
            postType: this.el.dataset.postType,
            data: []
        }

        this.page = 2
        this.postsPerPage = this.loadMore.dataset.postsPerPage

        if (this.ui.selects.length > 0) {
            on(this.ui.buttons, 'click', this.parseArgs)
        }

        if (this.ui.searchForm) {
            on(this.ui.searchForm, 'submit', this.searchFormValidation)
        }
    }

    parseArgs = (ev) => {
        let args = []

        this.addLoader()

        this.ui.selects.forEach(el => {
            const selectedOpts = [...el.options].filter(x => x.selected)
            const values = Array.from(selectedOpts).map(el => el.value)
            const taxonomy = el.dataset.taxonomy
            const year = el.dataset.year

            if (values.length > 0) {
                let data = Object.assign({},
                    values ? {values} : null,
                    year ? {year} : null,
                    taxonomy ? {taxonomy} : null,
                )
                args.push(data)
            }
        })

        this.payload.data = args

        this.getData().then((res) => {
            const items = JSON.parse(res)

            this.cardsGrid.innerHTML = ''
            if (this.pagination) {
                this.pagination.remove()
            }

            items.forEach(item => {
                this.cardsGrid.insertAdjacentHTML('beforeend', DOMPurify.sanitize(item))
            })

            if (this.loadMore) {
                this.loadMore.classList.remove('lb-load-more--hide')
                on(this.loadMoreBtn, 'click', this.loadMoreData)
            }
        }).then(() => {
            this.removeLoader()
            window.getCustomScrollbar.update()
        })
    }

    loadMoreData = () => {
        this.loadMoreBtn.disabled = true
        
        this.payload.page = this.page

        if (this.postsPerPage) {
            this.payload.posts_per_page = this.postsPerPage
        }

        this.getData().then((res) => {
            const items = JSON.parse(res)

            items.forEach(item => {
                this.cardsGrid.insertAdjacentHTML('beforeend', DOMPurify.sanitize(item))
            })
        }).then(() => {
            this.page++
            this.loadMoreBtn.disabled = false
            this.removeLoader()
            window.getCustomScrollbar.update()
        })
    }

    getData = async () => {
        let res = null
        
        try {
            const { data } = await axiosClient.get(`/wp-json/v1/archives`, {
                params: this.payload
            })
            res = data
        } catch (error) {
            console.error(error);
        }

        return res
    }

    searchFormValidation = (ev) => {
        const formData = new FormData(ev.target)
        const searchValue = formData.get("s")

        if (searchValue.length <= 2) {
            ev.preventDefault()
        }
    }

    addLoader = () => {
        // Disable selects
        if (this.ui.selects.length > 0) {
            this.ui.selects.forEach(el => {
                el.updateState('disable')
            })
        }

        // Disable search form
        if (this.ui.searchForm) {
            qs('input', this.ui.searchForm).updateState('disable')
        }
    }

    removeLoader = () => {
        // Active selects
        if (this.ui.selects.length > 0) {
            this.ui.selects.forEach(el => {
                el.updateState('active')
            })
        }

        // Active search form
        if (this.ui.searchForm) {
            qs('input', this.ui.searchForm).updateState('active')
        }
    }
}

export default Filters
