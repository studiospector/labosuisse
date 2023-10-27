<?php

namespace Caffeina\LaboSuisse\Blocks;

class NewsletterSubscription extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $search_form_visibility = get_field('lb_block_newsletter_subscription_search_visibility');
        $search_title = get_field('lb_block_newsletter_subscription_search_title');

        $payload = [
            'visibility' => get_field('lb_block_newsletter_subscription_search_visibility') == true,
            'images' => lb_get_images(get_field('lb_block_newsletter_subscription_image')),
            'search_form_action' => get_post_type_archive_link('lb-store'),
            'search_input' => $search_form_visibility ? [
                'type' => 'search',
                'id' => "lb-search-val-newsletter",
                'name' => "lb-search-val",
                'label' =>  __('Inserisci città, provincia, CAP,...', 'labo-suisse-theme'),
                'disabled' => false,
                'required' => false,
                'buttonTypeNext' => 'submit',
                'variants' => ['secondary'],
            ] : null,
            'search_infobox' => $search_title ? [
                'subtitle' => $search_title,
            ] : null,
            'form_infobox' => [
                'subtitle' => get_field('lb_block_newsletter_subscription_form_title'),
                'paragraph' => get_field('lb_block_newsletter_subscription_form_text'),
            ],
            'form_shortcode' => do_shortcode(get_field('lb_block_newsletter_subscription_form_shortcode')),
        ];

        $this->setContext($payload);
    }
}
