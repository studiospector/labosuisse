<?php

use Caffeina\LaboSuisse\Services\Google\Address;

require_once( '/var/www/html/wp-load.php' );

$distributors = new WP_Query([
    'post_status' => 'publish',
    'post_type' => 'lb-distributor',
    'posts_per_page' => -1,
    'suppress_filters' => false,
    'language' => 'en'
]);

global $sitepress;
$sitepress->switch_lang('en');

foreach ($distributors->get_posts() as $i => $distributor) {

        $rawAddress = get_field('lb_distributor_address', $distributor->ID);
        $translatedAddress = new Address($rawAddress['address'], 'EN');

        $data = new \stdClass();

        $data->address = $translatedAddress->formattedAddress;
        $data->lat = $translatedAddress->location->lat;
        $data->lng = $translatedAddress->location->lng;
        $data->zoom = 14;
        $data->place_id = $translatedAddress->placeId;
        $data->name = $translatedAddress->streetFull;
        $data->street_number = $translatedAddress->streetNumber;
        $data->street_name = $translatedAddress->street;
        $data->city = $translatedAddress->city;
        $data->state = $translatedAddress->region;
        $data->state_short = $translatedAddress->district_short;
        $data->district = $translatedAddress->district;
        $data->post_code = $translatedAddress->postalCode;
        $data->country = $translatedAddress->country;
        $data->country_short = $translatedAddress->countryShort;

        update_field('lb_distributor_address', $data, $distributor->ID);

        echo "{$distributor->post_title} updated!\n\n";
}
