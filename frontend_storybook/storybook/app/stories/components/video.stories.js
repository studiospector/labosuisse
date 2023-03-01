// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderHero from '../../views/components/video.twig'

import Video from '../../scripts/components/Video'

const data = {
    video_id: 'e53u_vQZAqY',
    provider: 'youtube',
}

storiesOf('Components|Video', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-video',
                        type: Video
                    },
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Base', () => renderHero(data))
