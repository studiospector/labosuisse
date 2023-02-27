<?php

namespace Caffeina\LaboSuisse\Blocks;

class Video extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $payload = [
            'video_id' => get_field('lb_block_video_id'),
            'provider' => get_field('lb_block_video_provider'),
        ];

        $this->setContext($payload);
    }
}
