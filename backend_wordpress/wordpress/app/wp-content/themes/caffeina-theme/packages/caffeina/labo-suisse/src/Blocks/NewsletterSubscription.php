<?php

namespace Caffeina\LaboSuisse\Blocks;

class NewsletterSubscription extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $payload = [
            'images' => lb_get_images(get_field('lb_block_newsletter_subscription_image')),
            'search_input' => [
                'type' => 'search',
                'id' => "lb-search-val-newsletter",
                'name' => "lb-search-val",
                'label' =>  __('Inserisci città, provincia, CAP,...', 'labo-suisse-theme'),
                'disabled' => false,
                'required' => false,
                'buttonTypeNext' => 'submit',
                'variants' => ['secondary'],
            ],
            'search_infobox' => [
                'subtitle' => get_field('lb_block_newsletter_subscription_search_title'),
            ],
            'form_infobox' => [
                'subtitle' => get_field('lb_block_newsletter_subscription_form_title'),
                'paragraph' => get_field('lb_block_newsletter_subscription_form_text'),
            ],
            'form_shortcode' => do_shortcode(get_field('lb_block_newsletter_subscription_form_shortcode')),
        ];

        $this->setContext($payload);
    }
}
