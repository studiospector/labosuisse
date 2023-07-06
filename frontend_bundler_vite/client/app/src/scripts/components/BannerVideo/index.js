import Component from "@okiba/component";
import { on, off, qs } from "@okiba/dom";

const ui = {
  nav: {
    selector: "#lb-offsetnav-banner-video",
  },
};

class BannerVideo extends Component {
  constructor({ options, ...props }) {
    super({ ...props, ui });

    this.scrollbarElem = qs(".js-scrollbar");

    this.scrollbarElem.append(this.ui.nav);
  }
}

export default BannerVideo;
