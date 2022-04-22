import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import axiosClient from '../HTTPClient'

import DOMPurify from 'dompurify'

const ui = {
    searchForm: '.lb-filters__search-form',
    searchFormInput: '.lb-filters__search-form .js-custom-input',
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

        this.filterType = this.el.dataset.filterType

        this.cardsGrid = qs('.js-lb-cards-grid')
        this.results = qs('.lb-posts-count')
        this.pagination = qs('.lb-pagination')
        this.loadMore = qs('.js-load-more')
        this.loadMoreBtn = (this.loadMore) ? qs('.button', this.loadMore) : null

        this.payload = {
            postType: this.el.dataset.postType,
            page: 1,
            posts_per_page: this.el.dataset.postsPerPage,
            data: []
        }

        this.loadMorePostsPerPage = (this.loadMore) ? this.loadMore.dataset.postsPerPage : null

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
        this.payload.page = 1

        // Render for Filter type Grid
        if (this.filterType == 'grid') {
            this.getData().then((res) => {
                this.cardsGrid.innerHTML = ''
                if (this.pagination) {
                    this.pagination.remove()
                }
                this.renderTypeGrid(JSON.parse(res))

            }).then(() => {
                this.removeLoader()
                window.getCustomScrollbar.update()
            })
            
        // Render for Filter type Map
        } else if (this.filterType == 'map-distributor') {
            this.renderTypeMapDistributor(args)
            this.removeLoader()
        }
    }

    loadMoreData = () => {
        this.loadMoreBtn.disabled = true

        if (this.loadMorePostsPerPage) {
            this.payload.posts_per_page = this.loadMorePostsPerPage
        }

        this.getData().then((res) => {
            this.renderTypeGrid(JSON.parse(res))
        }).then(() => {
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

    renderTypeGrid = (payload) => {
        const items = payload.posts

        if (this.results) {
            const resultsSpan = qs('span', this.results)
            resultsSpan.innerText = (payload.totalPosts > 0) ? DOMPurify.sanitize(payload.totalPosts) : 0
        }

        if (payload.totalPosts > 0) {
            items.forEach(item => {
                this.cardsGrid.insertAdjacentHTML('beforeend', DOMPurify.sanitize(item))
            })
        } else {
            this.cardsGrid.insertAdjacentHTML('beforeend', DOMPurify.sanitize(items))
        }

        if (this.loadMore) {
            if (payload.totalPosts > 0 && payload.hasPosts) {
                this.loadMore.classList.remove('lb-load-more--hide')
                on(this.loadMoreBtn, 'click', this.loadMoreData)
            } else {
                this.loadMore.classList.add('lb-load-more--hide')
                off(this.loadMoreBtn, 'click', this.loadMoreData)
            }
        }

        if (payload.hasPosts) {
            this.payload.page++
        }
    }

    renderTypeMapDistributor = (payload) => {
        let categories = []
        if (payload.length > 0) {
            categories = payload[0].values
        }
        this.filterMarkers(window.lbGMapLoaderDistributors, window.lbMapDistributors, categories)
    }

    /**
     * Filter markers by category
     */
    filterMarkers = (google, map, categories) => {
        let bounds = new google.maps.LatLngBounds()
        let countRes = 0

        for (let i = 0; i < map.markers.length; i++) {
            const marker = map.markers[i]
            
            if (categories.length > 0) {
                const success = categories.some((val) => marker.category.includes(parseInt(val)))
    
                // If is same category or category not picked
                if (success) {
                    marker.setVisible(true)
                    bounds.extend(marker.getPosition())
                    countRes++
                // Categories don't match 
                } else {
                    marker.setVisible(false)
                }
            } else {
                bounds.extend(marker.getPosition())
                marker.setVisible(true)
                countRes++
            }
        }

        // Center map
        map.fitBounds(bounds)
        if (countRes <= 1) {
            map.setZoom(8)
        }
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
            this.ui.searchFormInput.updateState('disable')
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
            this.ui.searchFormInput.updateState('active')
        }
    }
}

export default Filters
