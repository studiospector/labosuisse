<?php

namespace Caffeina\LaboSuisse\Actions\Store;

use Caffeina\LaboSuisse\Actions\Utils;
use WP_Query;

class AttachBeautySpecialistEvent
{
    use Utils;

    private $events = [];

    public function __construct($importId)
    {
        echo " [{$this->getTime()}] [Start] Attach beauty specialist events to stores\n\n";

        $this->events = $this->getEvents($importId);
    }

    public function start()
    {
        foreach ($this->events as $event) {
            $storeId = $this->getStoreId(
                get_field('lb_beauty_specialist_store_id', $event->ID)
            );

            if (!is_null($storeId)) {
                update_field('lb_beauty_specialist_store', $storeId, $event->ID);

                (new Store($storeId))->updatePhoneNumber(
                    get_field('lb_beauty_specialist_store_phone_number', $event->ID)
                );
            }
        }

        echo " [{$this->getTime()}] [Ended] Attach beauty specialist events to stores\n\n";
    }

    private function getEvents($importId)
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT post_id FROM wp_pmxi_posts WHERE import_id = {$importId}");

        $ids = array_map(function ($item) {
            return $item->post_id;
        }, $result);

        $query = new WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-beauty-specialist',
            'posts_per_page' => -1,
            'post__in' => $ids
        ]);

        return $query->get_posts();
    }

    private function getStoreId($store)
    {
        $storeId = null;

        $query = new WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-store',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'lb_stores_store_id',
                    'value' => $store,
                    'compare' => '='
                ]
            ]
        ]);

        $store = $query->get_posts();

        if (count($store) > 0) {
            $storeId = $store[0]->ID;
        }

        return $storeId;
    }
}
