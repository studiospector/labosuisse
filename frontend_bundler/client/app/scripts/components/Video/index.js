import Component from "@okiba/component";
import Plyr from "plyr";

const ui = {
  video: ".lb-video__player",
};

class Video extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui });

    console.log('this.ui.video', this.ui.video);

    if (this.ui.video) {
      const player = new Plyr(this.ui.video);

      console.log("player", player);

      if (player) {
        player.on("ended", (event) => {
          const instance = event.detail.plyr;
          instance.restart();
          setTimeout(() => instance.pause(), 100);
        });
      }
    }
  }
}

export default Video;
