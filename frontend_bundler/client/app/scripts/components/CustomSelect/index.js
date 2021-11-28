import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import CustomSelect from '../../vendors/custom-select'

const ui = {}

export default class LBCustomSelect extends Component {
    constructor({ options, ...props }) {
        super({ ...props, ui })
        
        new CustomSelect({
            selector: this.el,
            // debug: true
        });
    }
}
