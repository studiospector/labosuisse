<?php

namespace Caffeina\LaboSuisse\Option;

class Option
{
    public function getArchiveBrandLink()
    {
        return get_permalink($this->getOption('lb_archive_brand_link'));
    }

    public function getProductGalleryLink($productCategory)
    {
        $link = get_permalink($this->getOption('lb_archive_product_gallery_page'));

        return "{$link}?product_category={$productCategory}";
    }

    public function getLaboInTheWorldLink()
    {
        return [
            'crescina' => get_permalink($this->getOption('crescina_in_the_world_page')),
            'filerina' => get_permalink($this->getOption('filerina_in_the_world_page'))
        ];
    }

    public function getHeaderLinks()
    {
        $links = $this->prepareLinks('lb_header_links');

        $cart_items = is_object(WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;

        $items = [];
        foreach ($links as $i => $link) {
            $items[] = [
                'type' => 'icon',
                'mobile_hide' => $link['lb_header_links_mobile'],
                'icon' => ['name' => $link['lb_header_links_icon']],
                'href' => $link['lb_header_links_link']
            ];
        }

        $items = array_merge($items, [[
            'type' => 'icon',
            'mobile_hide' => false,
            'desktop_hide' => true,
            'icon' => ['name' => 'cart', 'counter' => $cart_items, 'counter_classes' => 'lb-wc-cart-total-count'],
            'class' => 'js-open-offset-nav',
            'attributes' => ['data-target-offset-nav="lb-offsetnav-async-cart"'],
        ]]);

        return $items;
    }

    public function getLShopLinks()
    {
        $links = $this->prepareLinks('lb_menu_shop_links');

        $items[] = ['type' => 'separator'];

        $cart_items = is_object(WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;

        $items[] = [
            'type' => 'nav',
            'icon' => ['name' => 'cart', 'counter' => $cart_items, 'counter_classes' => 'lb-wc-cart-total-count'],
            // 'href' => wc_get_cart_url(),
            'class' => 'js-open-offset-nav',
            'attributes' => ['data-target-offset-nav="lb-offsetnav-async-cart"'],
        ];

        $items[] = [
            'type' => 'icon',
            'icon' => ['name' => is_user_logged_in() ? 'user-filled' : 'user'],
            'href' => is_user_logged_in() ? get_permalink(get_option('woocommerce_myaccount_page_id')) : get_field('lb_shop_login_registration_page', 'option'),
        ];

        foreach ($links as $i => $link) {
            $items[] = [
                'type' => 'icon',
                'icon' => ['name' => $link['lb_menu_shop_links_icon']],
                'href' => $link['lb_menu_shop_links_link']
            ];
        }

        return $items;
    }

    public function getFaqOptions()
    {
        return [
            'title' => $this->getOption('lb_faq_title_option'),
            'description' => $this->getOption('lb_faq_description_option'),
            'infobox' => [
                'title' => $this->getOption('lb_faq_infobox_subtitle_option'),
                'paragraph' => $this->getOption('lb_faq_infobox_paragraph_option'),
                'cta' => array_merge($this->getOption('lb_faq_infobox_cta'), ['variants' => ['tertiary']])
            ]
        ];
    }

    public function getJobsOptions()
    {
        return [
            'title' => $this->getOption('lb_jobs_title_option'),
            'description' => $this->getOption('lb_jobs_description_option'),
            'infobox' => [
                'title' => $this->getOption('lb_jobs_infobox_subtitle_option'),
                'paragraph' => $this->getOption('lb_jobs_infobox_paragraph_option'),
                'cta' => array_merge($this->getOption('lb_jobs_infobox_cta'), ['variants' => ['tertiary']])
            ],

            'content' => [
                'company' => $this->getOption('lb_jobs_company_content'),
                'application' => $this->getOption('lb_jobs_application_content'),
            ]
        ];
    }

    public function getStoresOptions()
    {
        return [
            'title' => $this->getOption('lb_stores_title_option'),
            'description' => $this->getOption('lb_stores_description_option'),
            'card' => [
                'images' => lb_get_images($this->getOption('lb_stores_infobox_image')),
                'infobox' => [
                    'tagline' => $this->getOption('lb_stores_infobox_tagline'),
                    'subtitle' => $this->getOption('lb_stores_infobox_subtitle'),
                    'paragraph' => $this->getOption('lb_stores_infobox_paragraph'),
                    'cta' => array_merge($this->getOption('lb_stores_infobox_btn'), ['variants' => ['quaternary']])
                ],
                'type' => 'type-1-secondary',
                'variants' => null
            ],
        ];
    }

    public function getFooterMenuTitles()
    {
        $options = $this->getOption('lb_footer_menu_titles');

        return [
            'discoverLabo' => $options['lb_footer_menu_titles_discover_labo'] ?? null,
            'support' => $options['lb_footer_menu_titles_support'] ?? null,
        ];
    }

    public function getFooterSearchOptions()
    {
        $options = $this->getOption('lb_footer_search');

        if (is_null($options)) {
            return [
                'title' => null,
                'label' => null,
                'link' => null,
            ];
        }

        return [
            'title' => $options['lb_footer_search_title'],
            'label' => $options['lb_footer_search_label'],
            'link' => $options['lb_footer_search_link_eng'],
        ];
    }

    public function getFooterNewsletterOptions()
    {
        $options = $this->getOption('lb_footer_newsletter');

        if (is_null($options)) {
            return [
                'title' => null,
                'text' => null,
                'cta' => []
            ];
        }

        // Remove unused data
        unset($options['lb_footer_newsletter_cta']['url']);
        unset($options['lb_footer_newsletter_cta']['target']);

        return [
            'title' => $options['lb_footer_newsletter_title'],
            'text' => $options['lb_footer_newsletter_text'],
            'cta' => array_merge(
                $options['lb_footer_newsletter_cta'],
                [
                    'attributes' => ['data-target-offset-nav="lb-newsletter-nav"'],
                    'class' => 'js-open-offset-nav',
                    'variants' => ['secondary']
                ]
            ),
        ];
    }

    public function getFooterImpressumOptions()
    {
        $options = $this->getOption('lb_footer_impressum_text');

        if (is_null($options)) {
            return [
                'text' => null,
            ];
        }

        return [
            'text' => $options,
        ];
    }

    public function getFooterSocialNetwork()
    {
        $options = $this->getOption('lb_footer_social');

        if (is_null($options)) {
            return [
                'title' => null,
                'items' => []
            ];
        }

        $social = [
            'title' => $options['lb_footer_social_title'],
            'items' => []
        ];

        foreach ($options['lb_footer_social_links'] as $option) {
            $social['items'][] = [
                'url' => $option['lb_footer_social_links_link']['url'],
                'icon' => $option['lb_footer_social_links_icon']
            ];
        }

        return $social;
    }

    public function getPreFooterOptions()
    {
        return [
            'left' => $this->getOption('lb_prefooter_left_block'),
            'center' => $this->getOption('lb_prefooter_center_block'),
            'right' => $this->getOption('lb_prefooter_right_block'),
        ];
    }

    public function getMenuFixedCard($location, $device = 'desktop')
    {
        $cta = $this->getOption("lb_menu_fixed_card_{$location}_btn");

        if ($cta) {
            $cta = array_merge($this->getOption("lb_menu_fixed_card_{$location}_btn"), [ 'variants' => ['quaternary']]);
        } else {
            $cta = [];
        }

        return [
            'type' => 'card',
            'data' => [
                'images' => lb_get_images($this->getOption("lb_menu_fixed_card_{$location}_image")),
                'infobox' => [
                    'subtitle' => $this->getOption("lb_menu_fixed_card_{$location}_subtitle"),
                    'paragraph' => $this->getOption("lb_menu_fixed_card_{$location}_paragraph"),
                    'cta' => $cta
                ],
                'type' => ($device === 'desktop') ? 'type-3' : 'type-1',
                'variants' => null
            ],
        ];
    }

    public function getMenuFixedCardByTaxTerm($term, $device = 'desktop')
    {
        $cta = get_field('lb_product_cat_menu_link', $term);

        if ($cta) {
            $cta = array_merge(get_field('lb_product_cat_menu_link', $term), [ 'variants' => ['quaternary']]);
        } else {
            $cta = [];
        }

        return [
            'type' => 'card',
            'data' => [
                'images' => lb_get_images(get_field('lb_product_cat_menu_image', $term)),
                'infobox' => [
                    'subtitle' => get_field('lb_product_cat_menu_subtitle', $term),
                    'paragraph' => get_field('lb_product_cat_menu_paragraph', $term),
                    'cta' => $cta
                ],
                'type' => ($device === 'desktop') ? 'type-3' : 'type-1',
                'variants' => null
            ],
        ];
    }

    public function getApiKey($service)
    {
        return $this->getOption($service);
    }

    public function orderExportIsActive()
    {
        return $this->getOption('lb_order_export_is_active') ?? false;
    }

    public function getOrderExportMailingList()
    {
        $recipients = [];

        $items = $this->getOption('lb_order_export_mailing_list');

        if ($items) {
            foreach ($items as $item) {
                $recipients[] = $item['lb_order_export_mailing_list_item'];
            }
        }

        return $recipients;
    }

    public function getMessages()
    {
        $messages = $this->getOption('lb_messages');

        if (empty($messages)) {
            return [];
        }

        return $messages;
    }

    private function prepareLinks($name)
    {
        $links = $this->getOption($name);

        if(empty($links)) {
            $links = [];
        }

        return $links;
    }

    private function getOption($name)
    {
        return get_field($name, 'option');
    }
}
