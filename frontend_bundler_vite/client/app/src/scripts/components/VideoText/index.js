import Component from "@okiba/component";
import Plyr from "plyr";

const ui = {
  video: ".lb-video-text__player",
};

class VideoText extends Component {
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
      const player = new Plyr(this.ui.video, plyrProps);

      if (!this.loop) {
        player.on("ended", (event) => {
          const instance = event.detail.plyr;
          instance.restart();
          setTimeout(() => instance.pause(), 100);
        });
      }
    }
  }
}

export default VideoText;
