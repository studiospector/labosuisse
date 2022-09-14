<?php

namespace Caffeina\LaboSuisse\Api\Multicountry;

class Geolocation
{
    public function get()
    {
        $response = [
            'infobox' => [
                'paragraph' => "Looks like you're in Spain!<br>You want to visit the Labo Suisse International Website?"
            ],
            'buttons' => [
                [
                    'title' => 'Confirm',
                    'url' => '#',
                    'variants' => ['primary'],
                ],
                [
                    'title' => 'No, continue with Italian Website',
                    'class' => 'js-close-offset-nav',
                    'attributes' => 'data-target-offset-nav="lb-offsetnav-multicountry-geolocation"',
                    'iconEnd' => [],
                    'variants' => ['quaternary'],
                ]
            ]
        ];

        return $response;
    }
}
