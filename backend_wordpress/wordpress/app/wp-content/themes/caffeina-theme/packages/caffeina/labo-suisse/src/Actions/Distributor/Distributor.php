<?php

namespace Caffeina\LaboSuisse\Actions\Distributor;

use Caffeina\LaboSuisse\Actions\Utils;
use Caffeina\LaboSuisse\Services\Google\Address;

class Distributor
{
    use Utils;

    private $distributors = [];

    public function __construct($importId)
    {
//        echo " [{$this->getTime()}] [Start] Update Distributors ACF\n\n";

        $this->distributors = $this->getPosts($importId);
    }

    public function update()
    {
        foreach ($this->distributors as $item) {
            $this->updateAddress($item);
        }

        echo " [{$this->getTime()}] [Ended] Update Distributor ACF\n\n";
    }

    private function getPosts($importId)
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT post_id FROM wp_pmxi_posts WHERE import_id = {$importId}");

        $ids = array_map(function ($item) {
            return $item->post_id;
        }, $result);

        $query = new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-distributor',
            'posts_per_page' => -1,
            'post__in' => $ids
        ]);

        return $query->get_posts();
    }

    private function updateAddress($post)
    {
        $rawAddress = get_field('lb_distributor_address', $post->ID)['address'];
        $address = new Address($rawAddress);

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

        update_field('lb_distributor_address', $data, $post->ID);
    }
}
