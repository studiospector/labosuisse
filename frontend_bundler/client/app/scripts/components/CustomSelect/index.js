import Component from '@okiba/component'

import CustomSelect from '../../vendors/custom-select'

const ui = {}

class LBCustomSelect extends Component {
    
    constructor({ options, ...props }) {
        super({ ...props, ui })

        new CustomSelect({
            selector: this.el,
            // debug: true
        });
    }
}

export default LBCustomSelect
