<?php

namespace Caffeina\LaboSuisse\Actions\Store;

use Caffeina\LaboSuisse\Services\Google\Address;

class UpdateGoogleMapsData
{
    private $stores = [];

    public function __construct()
    {
        $this->stores = $this->getStores();
    }


    public function start()
    {
        foreach ($this->stores as $store) {
            $address = new Address($store->post_content);

            $value = $this->prepareData($address);

            update_field('lb_stores_gmaps_point', $value, $store->ID);

        }
    }

    private function getStores()
    {
        $query = new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-store',
            'posts_per_page' => -1
        ]);

        return $query->get_posts();
    }

    private function prepareData($address)
    {
        $data = new \stdClass();

        $data->address = $address->formattedAddress;
        $data->lat = $address->location->lat;
        $data->lng = $address->location->lng;
        $data->zoom = 14;
        $data->place_id = $address->placeId;
        $data->name = $address->streetFull;
        $data->street_number = $address->streetNumber;
        $data->street_name = $address->street;
        $data->city = $address->city;
        $data->state = $address->region;
        $data->state_short = $address->district_short;
        $data->district = $address->district;
        $data->post_code = $address->postalCode;
        $data->country = $address->country;
        $data->country_short = $address->countryShort;

        return $data;
    }
}