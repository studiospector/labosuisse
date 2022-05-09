import Component from '@okiba/component'

import AnimationImage from './AnimationImage'
import AnimationText from './AnimationText'
import AnimationCard from './AnimationCard'

const ui = {
    images: {
        selector: '.js-image-reveal',
        asArray: true,
    },
    texts: {
        selector: '.js-text-reveal',
        asArray: true,
    },
    grids: {
        selector: '.js-card-reveal',
        asArray: true,
    },
}

class AnimationReveal extends Component {
    constructor({el}) {
        super({el, ui})

        this.init()
    }

    init = () => {
        this.ui.images.forEach(image => new AnimationImage({el: image}))
        this.ui.texts.forEach(text => new AnimationText({el: text}))
        this.ui.grids.forEach(grid => new AnimationCard({el: grid}))
    }
}

export default AnimationReveal
