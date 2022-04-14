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

        foreach ($query->get_posts() as $post) {


            $distributor = [
                'title' => $post->post_title,
                'content' => $post->post_content,
                'phone' => get_field('lb_distributor_phone', $post->ID),
                'email' => get_field('lb_distributor_email', $post->ID),
                'address' => get_field('lb_distributor_address', $post->ID),
                'brands' => $this->getBrands($post),
                'links' => []
            ];

            foreach (get_field('lb_distributor_links', $post->ID) as $link) {
                $distributor['links'][] = [
                    'label' => $link['lb_distributor_links_label'],
                    'links' => $this->parseLinks($link['lb_distributor_links_list']),
                ];
            }

            $distributors[] = $distributor;
        }

        return rest_ensure_response($distributors);
    }

    private function getBrands($post)
    {
        $brands = wp_get_post_terms($post->ID, 'lb-brand', ['parent' => null]);

        return array_map(function ($item) {
            return [
                'id' => $item->term_id,
                'name' => $item->name
            ];
        }, $brands);
    }

    private function parseLinks($links)
    {
        return array_map(function ($item) {
            return [
                'icon' => $item['lb_distributor_links_list_icon'],
                'link' => $item['lb_distributor_links_list_link']
            ];
        }, $links);
    }
}
