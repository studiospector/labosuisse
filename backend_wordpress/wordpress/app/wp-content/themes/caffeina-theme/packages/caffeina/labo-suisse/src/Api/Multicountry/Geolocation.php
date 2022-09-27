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
        $payload = [];
        $geolocation = $this->geolocate();

        $countryCode = $geolocation['countryCode'];
        $country = $geolocation['country'];

        if (
            ($countryCode == 'IT' && $this->curr_lang == 'IT') ||
            ($countryCode != 'IT' && $this->curr_lang != 'IT')
        ) {
            return null;
        }

        if ($countryCode != 'IT' && $this->curr_lang == 'IT') {
            $payload = $this->redirectToEnglishSite($country);
        } else if ($countryCode == 'IT' && $this->curr_lang != 'IT') {
            $payload = $this->redirectToMainSite();
        }

        return [
            'curr_lang' => $this->curr_lang,
            'geolocation' => $geolocation,
            'data' => [
                'infobox' => [
                    'paragraph' => $payload['text'],
                ],
                'buttons' => $payload['buttons']
            ]
        ];
    }

    private function geolocate()
    {
        $ip = \WC_Geolocation::get_ip_address();
        $geolocationInfo = \WC_Geolocation::geolocate_ip($ip);

        //for local environment
        if (empty($geolocationInfo['country'])) {
            $ip = \WC_Geolocation::get_external_ip_address();
            $geolocationInfo = \WC_Geolocation::geolocate_ip($ip);
        }

        return array_merge($geolocationInfo, $this->getLocationInfo($ip));
    }

    private function getLocationInfo($ip)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "http://ip-api.com/json/{$ip}",
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        return [
            'country' => $response->country,
            'countryCode' => $response->countryCode
        ];
    }

    private function redirectToEnglishSite($country)
    {
        return [
            'text' => "Looks like you're in {$country}!<br>You want to visit the Labo Suisse International Website?",

            'buttons' => [
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
            ]
        ];
    }

    private function redirectToMainSite()
    {
        return [
            'text' => "Sembra tu sia in Italia!<br>Vuoi visitare il sito Italiano di Labo Suisse?",
            'buttons' => [
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
            ]
        ];
    }
}
