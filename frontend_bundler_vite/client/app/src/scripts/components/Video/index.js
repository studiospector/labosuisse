import Component from "@okiba/component";
import { on, qs } from "@okiba/dom";
import Plyr from "plyr";

const ui = {
  video: ".lb-video__player",
};

class Video extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui });

    this.noControls = this.el.dataset?.noControls
    this.autoplay = this.el.dataset?.autoplay
    this.loop = this.el.dataset?.loop

    const plyrProps = Object.assign(
      {},
      !!this.noControls ? { controls: [] } : null,
      !!this.autoplay ? { autoplay: true } : null,
      !!this.autoplay ? { muted: true } : null,
      !!this.loop ? { loop: {active: true} } : null,
    )

    if (this.ui.video) {
      this.player = new Plyr(this.ui.video, plyrProps);

      this.nav = qs('#lb-offsetnav-banner-video')
      if (this.nav) {
        on(this.nav, 'closeOffsetNav', this.close)
      }

      if (!this.loop) {
        this.player.on("ended", (event) => {
          const instance = event.detail.plyr;
          instance.restart();
          setTimeout(() => instance.pause(), 100);
        });
      }
    }
  }

  close = () => {
    this.player.pause()
  }
}

export default Video;
