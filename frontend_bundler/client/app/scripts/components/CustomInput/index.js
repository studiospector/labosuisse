import Component from '@okiba/component'

import CustomInput from '../../vendors/custom-input'

class LBCustomInput extends Component {
    
    constructor({ options, ...props }) {
        super({ ...props })

        new CustomInput({
            selector: this.el
        });
    }
}

export default LBCustomInput
