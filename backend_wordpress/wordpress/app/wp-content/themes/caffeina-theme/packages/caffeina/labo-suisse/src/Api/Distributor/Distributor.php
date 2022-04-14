<?php

namespace Caffeina\LaboSuisse\Api\Distributor;

use Caffeina\LaboSuisse\Resources\Distributor as DistributorResource;

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

        foreach ($query->get_posts() as $post) {
            $distributors[] = (new DistributorResource($post))->toArray();
        }

        return rest_ensure_response($distributors);
    }
}
