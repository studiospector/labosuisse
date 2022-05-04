<?php

namespace Caffeina\LaboSuisse\Resources\Traits;

trait HasImages
{
    private function getImages()
    {
        return lb_get_images(get_post_thumbnail_id($this->post->ID));
    }
}
