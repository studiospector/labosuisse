import Component from '@okiba/component'

import { on } from '@okiba/dom'

class GTMTracking extends Component {
    constructor({el}) {
        super({el})

        this.event = this.el.dataset.gaEvent
        this.eventName = this.el.dataset.gaEventName
        this.eventValue = this.el.dataset.gaEventValue

        on(this.el, this.event, this.traceEvent)

        this.traceMailchimp()
    }

    traceEvent = (ev) => {
        let data = {
            event: this.eventName,
        }

        Object.assign(data, this.eventValue && { value: this.eventValue })

        dataLayer.push(data)
    }

    traceMailchimp = () => {
        if (typeof mc4wp !== 'undefined') {
            mc4wp.forms.on('subscribed', (form) => {
                dataLayer.push({
                    event: 'form-newsletter',
                    value: form.name
                })
            })
        }
    }
}

export default GTMTracking
