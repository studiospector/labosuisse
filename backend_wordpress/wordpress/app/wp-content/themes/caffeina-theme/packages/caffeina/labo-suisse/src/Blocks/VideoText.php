<?php

namespace Caffeina\LaboSuisse\Blocks;

class VideoText extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $payload = [
            'tagline' => get_field('lb_block_video_text_tagline'),
            'title' => get_field('lb_block_video_text_title'),
            'paragraph' => get_field('lb_block_video_text_paragraph'),
            'visibility' => get_field('lb_block_video_text_visibility') == true,
            'videoUrl' => get_field('lb_block_video_text_url')
        ];

        $this->setContext($payload);
    }
}
