<?php

namespace Caffeina\LaboSuisse\Blocks;
use Timber\Timber;

class BannerVideo extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $nav = [
            'id' => 'lb-offsetnav-banner-video',
            'title' => null,
            'data' => [
                [
                    'type' => 'html',
                    'data' => Timber::compile('@PathViews/components/video.twig', [
                        'provider' => 'youtube',
                        'video_id' => get_field('lb_block_banner_video_id'),
                    ]),
                ]
            ],
            'noClose' => false,
            'variants' => ['popup', 'popup-wide']
        ];

        $payload = [
            'video' => [
                'provider' => 'youtube',
                'video_id' => get_field('lb_block_banner_video_id'),
                'attributes' => ['data-no-controls="true"', 'data-autoplay="true"', 'data-loop="true"'],
            ],
            'btnLabel' => get_field('lb_block_banner_video_infobox_btn_label'),
            'btnVariant' => get_field('lb_block_banner_video_infobox_btn_variants'),
            'infoboxTextAlignment' => get_field('lb_block_banner_video_infoboxtextalignment'),
            'variants' => [get_field('lb_block_banner_video_variants')],
            'nav' => $nav,
        ];

        $this->setContext($payload);

        $this->addInfobox();
    }
}
