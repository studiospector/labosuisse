// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/video.twig";
import Video from "../../scripts/components/Video";

export default {
  title: "Components/Video",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-video",
              type: Video,
            },
          ],
        });

        return () => app.destroy();
      }, []);

      return `
        <div>
          ${Story().outerHTML || Story()}
        </div>
      `;
    },
  ],
  render: ({ ...args }) => {
    return render(args);
  },
};

/**
 * Base
 */
export const Base = {
  args: {
    video_id: "e53u_vQZAqY",
    provider: "youtube",
  },
};
