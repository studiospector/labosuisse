import Component from "@okiba/component";
import Plyr from "plyr";

const ui = {
  video: "[data-video]",
};

class Video extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui });

    if (this.ui.video) {
      const player = new Plyr(this.ui.video);

      player.on("ended", (event) => {
        const instance = event.detail.plyr;
        instance.restart();
        setTimeout(() => instance.pause(), 100);
      });
    }
  }
}

export default Video;
