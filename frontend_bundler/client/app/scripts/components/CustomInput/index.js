import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import CustomInput from '../../vendors/custom-input'

export default class LBCustomInput extends Component {
    constructor({ options, ...props }) {
        super({ ...props })

        new CustomInput({
            selector: this.el
        });
    }
}
