<?php

namespace Caffeina\LaboSuisse\Resources;

use Caffeina\LaboSuisse\Services\Province;
use Carbon\Carbon;

class BeautySpecialist
{
    private $today = null;
    private $city = null;
    private $showExpired = false;

    public function __construct($city)
    {
        $this->today = Carbon::now()->format('Ymd');

        $this->showExpired = (isset($_GET['show_expired']) and $_GET['show_expired'] == true);

        $this->city = ($city)
            ? Province::getShortCode($city)
            : null;
    }

    public function all()
    {
        if (is_null($this->city)) {
            return [
                'items' => null,
                'count' => null
            ];
        }

        $stores = $this->getStores($this->city);

        if (count($stores) == 0) {
            return [
                'items' => [],
                'count' => 0
            ];
        }

        $items = [];
        $posts = $this->getBeautySpecialist($stores);

        foreach ($posts as $post) {
            $store = get_post(get_field('lb_beauty_specialist_store', $post->ID));
            $date = get_field('lb_beauty_specialist_date', $post->ID);
            [$address, $formattedAddress] = $this->getAddress($store->ID);

            $items[$date]['date'] = $this->getDate($date);
            $items[$date]['items'][] = [
                'store_id' => get_field('lb_beauty_specialist_store', $post->ID),
                'expired' => strtotime($date) < strtotime('now'),
                'date' => $date,
                'store' => $store->post_title,
                'address' => $formattedAddress,
                'phone' => get_field('lb_stores_phone_number', $store->ID),
                'geo_location' => $address,
            ];
        }

        ksort($items);

        return [
            'items' => $items,
            'count' => count($posts)
        ];
    }

    private function getDate($date)
    {
        $localizedDate = Carbon::createFromFormat('Ymd', $date)->locale('it_IT');

        return ucfirst($localizedDate->dayName) . ", {$localizedDate->day} " . ucfirst($localizedDate->monthName) . " {$localizedDate->year}";
    }

    private function getStores($city)
    {
        $stores = new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-store',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'lb_stores_province',
                    'value' => $this->city,
                    'compare' => 'LIKE'
                ]
            ]
        ]);

        return $stores->get_posts();
    }

    private function getBeautySpecialist($stores)
    {
        $args = [
            'post_status' => 'publish',
            'post_type' => 'lb-beauty-specialist',
            'meta_query' => [[
                'key' => 'lb_beauty_specialist_store',
                'value' => $stores,
                'compare' => 'IN'
            ]],
            'meta_key' => 'lb_beauty_specialist_date',
            'orderby' => 'lb_beauty_specialist_date',
            'order' => 'DESC',
            'posts_per_page' => -1
        ];

        if (!$this->showExpired) {
            $args['meta_query'][] = [
                'key' => 'lb_beauty_specialist_date',
                'value' => $this->today,
                'compare' => '>='
            ];
        }

        $query = new \WP_Query($args);

        return $query->get_posts();
    }

    private function getAddress($storeId)
    {
        $address = get_field('lb_stores_gmaps_point', $storeId);

        $streetName = $address['street_name'] ?? null;
        $streetNumber = $address['street_number'] ?? null;
        $city = $address['city'] ?? null;
        $postCode = $address['post_code'] ?? null;
        $stateShort = $address['state_short'] ?? null;


        $formatedAddress =  "{$streetName}, {$streetNumber} - {$postCode} - {$city} ({$stateShort})";

        return [
            $address,
            $formatedAddress
        ];
    }
}
