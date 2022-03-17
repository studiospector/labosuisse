<?php

namespace Caffeina\LaboSuisse\Resources;

class BeautySpecialist
{
    private $today = null;
    private $city = null;

    public function __construct()
    {
        $this->city = $_GET['city'] ?? null;
        $this->today  = date("Ymd",mktime(0,0,0,date("m"),date("d"),date("Y")));
    }

    public function all()
    {
        $items = [];

        if(is_null($this->city)) {
            return $items;
        }

        $stores = $this->getStores($_GET['city']);
        $posts = $this->getBeautySpecialist($stores);

        foreach ($posts as $post) {
            $store = get_post(get_field('lb_beauty_specialist_store', $post->ID));
            $date = get_field('lb_beauty_specialist_date', $post->ID);

            $items[$date]['date'] = \DateTime::createFromFormat('Ymd',$date)->format('l, d F Y');
            $items[$date]['items'][] = [
                'store_id' => get_field('lb_beauty_specialist_store', $post->ID),
                'expired' => strtotime($date) < strtotime('now'),
                'date' => $date,
                'store' => $store->post_title,
                'address' => $store->post_content,
                'phone' => get_field('lb_stores_phone_number', $store->ID),
            ];
        }

        ksort($items);

        return $items;
    }

    private function getStores($city)
    {
        $stores = new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-store',
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'lb_stores_gmaps_point',
                    'value' => '"' . $city . '"',
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
            'order' => 'DESC'
        ];

        if(!isset($_GET['show_expired'])) {
            $args['meta_query'][] = [
                'key' => 'lb_beauty_specialist_date',
                'value' => $this->today,
                'compare' => '>='
            ];
        }

        $query = new \WP_Query($args);

        return $query->get_posts();
    }
}
