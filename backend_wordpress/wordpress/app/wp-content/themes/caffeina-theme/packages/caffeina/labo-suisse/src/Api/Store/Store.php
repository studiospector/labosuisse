<?php

namespace Caffeina\LaboSuisse\Api\Store;

class Store
{
    public function all()
    {
        $stores = [];

        $query = new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-store',
            'posts_per_page' => -1
        ]);

        foreach ($query->get_posts() as $post) {
            $stores[] = [
                'store' => $post->post_title,
                'address' => $post->post_content,
                'phone' => get_field('lb_stores_phone_number', $post->ID),
                'geo_location' => get_field('lb_stores_gmaps_point', $post->ID),
                'open_until' => $this->getOpenUntil($post->ID),
                'timetable' => [
                    'monday' => get_field('lb_stores_time_table_monday', $post->ID),
                    'tuesday' => get_field('lb_stores_time_table_tuesday', $post->ID),
                    'wednesday' => get_field('lb_stores_time_table_wednesday', $post->ID),
                    'thursday' => get_field('lb_stores_time_table_thursday', $post->ID),
                    'friday' => get_field('lb_stores_time_table_friday', $post->ID),
                    'saturday' => get_field('lb_stores_time_table_saturday', $post->ID),
                    'sunday' => get_field('lb_stores_time_table_sunday', $post->ID),
                ]
            ];
        }

        return rest_ensure_response($stores);
    }

    private function getOpenUntil($postId)
    {
        $day = strtolower(date('l'));

        $interval = explode(', ', get_field('lb_stores_time_table_' . $day, $postId));

        $morning = explode('-', $interval[0]);
        $afternoon = explode('-', $interval[1] ?? null);

        if (count($interval) === 1 or count($afternoon) == 1) {
            return end($morning);
        }

        return end($afternoon);
    }
}
