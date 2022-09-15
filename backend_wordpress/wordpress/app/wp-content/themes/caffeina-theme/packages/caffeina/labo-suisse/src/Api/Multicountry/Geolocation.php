<?php

namespace Caffeina\LaboSuisse\Api\Multicountry;

class Geolocation
{
    private $curr_lang;

    public function __construct($curr_lang)
    {
        $this->curr_lang = strtoupper($curr_lang);
    }

    public function get()
    {
        $text = '';
        $buttons = [];
        $geolocation = $this->geolocate();

        if ($geolocation['data']['country'] == 'IT' && $this->curr_lang == 'IT') {
            return null;
        } else if ($geolocation['data']['country'] != 'IT' && $this->curr_lang != 'IT') {
            return null;
        } else if ($geolocation['data']['country'] != 'IT' && $this->curr_lang == 'IT') {
            $text = "Looks like you're in {$geolocation['data']['country']}!<br>You want to visit the Labo Suisse International Website?";
            $buttons = [
                [
                    'title' => 'Confirm',
                    'url' => home_url() . '/en',
                    'variants' => ['primary'],
                ],
                [
                    'title' => 'No, continue with Italian Website',
                    'class' => 'js-close-offset-nav',
                    'attributes' => 'data-target-offset-nav="lb-offsetnav-multicountry-geolocation"',
                    'iconEnd' => [],
                    'variants' => ['quaternary'],
                ]
            ];
        } else if ($geolocation['data']['country'] == 'IT' && $this->curr_lang != 'IT') {
            $text = "Sembra tu sia in Italia!<br>Vuoi visitare il sito Italiano di Labo Suisse?";
            $buttons = [
                [
                    'title' => 'Conferma',
                    'url' => home_url(),
                    'variants' => ['primary'],
                ],
                [
                    'title' => 'No, continua con il sito Internazionale',
                    'class' => 'js-close-offset-nav',
                    'attributes' => 'data-target-offset-nav="lb-offsetnav-multicountry-geolocation"',
                    'iconEnd' => [],
                    'variants' => ['quaternary'],
                ]
            ];
        }

        $response = [
            'curr_lang' => $this->curr_lang,
            'geolocation' => $geolocation,
            'data' => [
                'infobox' => [
                    'paragraph' => $text,
                ],
                'buttons' => $buttons
            ]
        ];

        return $response;
    }

    private function geolocate()
    {
        $user_ip = \WC_Geolocation::get_ip_address();
        $user_geodata_wc = \WC_Geolocation::geolocate_ip($user_ip);
        
        return [
            'ip' => $user_ip,
            'data' => $user_geodata_wc,
        ];
    }
}
