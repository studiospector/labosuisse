import Component from '@okiba/component'

import { on } from '@okiba/dom'

class GTMTracking extends Component {
    constructor({el}) {
        super({el})

        this.eventType = this.el.dataset.gaEventType ? this.el.dataset.gaEventType : 'local'
        this.event = this.el.dataset.gaEvent
        this.eventName = this.el.dataset.gaEventName
        this.eventValue = this.el.dataset.gaEventValue

        if (this.eventType == 'local') {
            on(this.el, this.event, this.traceEvent)
        } else if (this.eventType == 'global') {
            this.traceGlobalEvents()
        }
    }

    traceEvent = (ev) => {
        let data = {
            event: this.eventName,
        }

        Object.assign(data, this.eventValue && { value: this.eventValue })

        dataLayer.push(data)
    }

    traceGlobalEvents = () => {
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
