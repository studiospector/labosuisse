<?php

namespace Caffeina\LaboSuisse\Api\Multicountry;

class Geolocation
{
    public function get()
    {
        $geolocate = $this->geolocate();
        $response = [
            'geolocation' => $geolocate,
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

    private function geolocate()
    {
        $wc_geo_instance  = new \WC_Geolocation();
        $user_ip = $wc_geo_instance->get_ip_address();
        $user_geodata_wc = $wc_geo_instance->geolocate_ip($user_ip);

        $maxmind_geo_instance  = new \WC_Integration_MaxMind_Geolocation();
        $user_geodata_maxmind = $maxmind_geo_instance->get_geolocation(null, $user_ip);
        
        return [
            'wc' => $user_geodata_wc,
            'maxmind' => $user_geodata_maxmind,
        ];
    }
}
