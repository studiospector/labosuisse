<?php

namespace Caffeina\LaboSuisse\Services\Google;

use Caffeina\LaboSuisse\Option\Option;

class Address
{
    private $apiKey = null;

    public $street = null;
    public $streetNumber = null;
    public $streetFull = null;
    public $city = null;
    public $district = null;
    public $district_short = null;
    public $postalCode = null;
    public $region = null;
    public $country = null;
    public $countryShort = null;
    public $formattedAddress = null;
    public $location = null;
    public $placeId = null;

    public function __construct($address)
    {
        $this->apiKey = $this->retrieveApiKey();

        $this->getAddressDetailsFromGoogle($address);
    }

    private function getAddressDetailsFromGoogle($address)
    {
        $curl = curl_init();

        $queryParams = http_build_query([
            'address' => $address,
            'key' => $this->apiKey,
            'language' => 'IT'
        ]);

        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?{$queryParams}",
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        $this->parseGoogleResult($response);
    }

    private function parseGoogleResult($response)
    {
        $address = $response->results[0];

        $this->formattedAddress = $address->formatted_address;
        $this->location = $address->geometry->location;
        $this->placeId = $address->place_id;

        foreach ($address->address_components as $addressComponent) {
            if (in_array('street_number', $addressComponent->types)) {
                $this->streetNumber = $addressComponent->short_name;
            }

            if (in_array('route', $addressComponent->types)) {
                $this->street = $addressComponent->long_name;
            }

            if (in_array('administrative_area_level_3', $addressComponent->types)) {
                $this->city = $addressComponent->short_name;
            }

            if (in_array('administrative_area_level_2', $addressComponent->types)) {
                $this->district =  $addressComponent->long_name;
                $this->district_short = $addressComponent->short_name;
            }

            if (in_array('postal_code', $addressComponent->types)) {
                $this->postalCode = $addressComponent->short_name;
            }

            if (in_array('administrative_area_level_1', $addressComponent->types)) {
                $this->region = $addressComponent->short_name;
            }

            if (in_array('country', $addressComponent->types)) {
                $this->country = $addressComponent->long_name;
                $this->countryShort = $addressComponent->short_name;
            }
        }

        $this->streetFull = "{$this->street}, {$this->streetNumber}";
    }

    private function retrieveApiKey()
    {
        $option = new Option();

        return $option->getApiKey('lb_api_key_google_maps');
    }
}
