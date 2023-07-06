import Component from "@okiba/component";
import { on, qs } from "@okiba/dom";
import Plyr from "plyr";

const ui = {
  video: ".lb-video__player",
};

class Video extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui });

    this.player = null

    this.asyncInit = this.el.dataset?.asyncInit
    this.isFirstInit = true

    this.noControls = this.el.dataset?.noControls
    this.autoplay = this.el.dataset?.autoplay
    this.loop = this.el.dataset?.loop

    this.plyrProps = Object.assign(
      {},
      !!this.noControls ? { controls: [] } : null,
      !!this.autoplay ? { autoplay: true } : null,
      !!this.autoplay ? { muted: true } : null,
      !!this.loop ? { loop: {active: true} } : null,
    )

    if (this.ui.video) {
      this.nav = qs('#lb-offsetnav-banner-video')
      
      if (this.nav && this.nav.contains(this.el)) {
        on(this.nav, 'closeOffsetNav', this.close)

        if (!!this.asyncInit) {
          on(this.nav, 'openOffsetNav', this.handleAsyncInit)
        }
      } else {
        this.init()
      }
    }
  }

  init = () => {
    this.player = new Plyr(this.ui.video, this.plyrProps);

    if (!this.loop) {
      this.player.on("ended", (event) => {
        const instance = event.detail.plyr;
        instance.restart();
        setTimeout(() => instance.pause(), 100);
      });
    }
  }

  handleAsyncInit = () => {
    if (this.isFirstInit) {
      this.init()
      this.isFirstInit = false
    }
  }

  close = () => {
    this.player.pause()
  }
}

export default Video;
