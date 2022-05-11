import Component from '@okiba/component'

import { on } from '@okiba/dom'

class GTMTracking extends Component {
    constructor({el}) {
        super({el})

        this.event = this.el.dataset.gaEvent
        this.eventName = this.el.dataset.gaEventName
        this.eventValue = this.el.dataset.gaEventValue

        on(this.el, this.event, this.traceEvent)
    }

    traceEvent = (ev) => {
        let data = {
            event: this.eventName,
        }

        Object.assign(data, this.eventValue && { value: this.eventValue })

        dataLayer.push(data)
    }
}

export default GTMTracking
