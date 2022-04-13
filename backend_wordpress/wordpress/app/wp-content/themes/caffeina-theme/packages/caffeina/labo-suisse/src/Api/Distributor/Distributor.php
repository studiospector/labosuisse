<?php

namespace Caffeina\LaboSuisse\Api\Distributor;

class Distributor
{
    public function all()
    {
        $distributors = [];

        $query = new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'lb-distributor',
            'posts_per_page' => -1
        ]);

        foreach ($query->get_posts() as $distributor) {
            $distributors[] = [
                'title' => $distributor->post_title,
                'content' => $distributor->post_content,
                'phone' => get_field('lb_distributor_phone', $distributor->ID),
                'email' => get_field('lb_distributor_email', $distributor->ID),
                'address' => get_field('lb_distributor_address', $distributor->ID),
            ];
        }

        return rest_ensure_response($distributors);
    }
}
