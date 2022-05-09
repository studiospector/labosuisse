<?php

namespace Caffeina\LaboSuisse\Actions\Distributor;

class UpdateGoogleMapsData extends \Caffeina\LaboSuisse\Actions\UpdateGoogleMapsData
{
    public $postType = 'lb-distributor';
    public $field = 'lb_distributor_address';

    protected function getRawData($post)
    {
        $address = get_field('lb_distributor_address', $post->ID);

        return $address['address'];
    }
}
