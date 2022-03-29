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

        this.payload = {
            postType: this.el.dataset.postType,
            data: []
        }

        if (this.ui.buttons.length > 0) {
            on(this.ui.buttons, 'click', this.parseArgs)
        }

        if (this.ui.searchForm) {
            on(this.ui.searchForm, 'submit', this.searchFormValidation)
        }
    }

    parseArgs = (ev) => {
        let args = []

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

            items.forEach(item => {
                this.cardsGrid.insertAdjacentHTML('beforeend', DOMPurify.sanitize(item))
            })
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
}

export default Filters
