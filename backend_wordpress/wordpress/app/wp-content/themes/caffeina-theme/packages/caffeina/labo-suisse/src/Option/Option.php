<?php

namespace Caffeina\LaboSuisse\Option;

class Option
{
    public function getArchiveBrandLink()
    {
        return get_permalink($this->getOption('lb_archive_brand_link'));
    }

    public function getHeaderLinks()
    {
        $links = $this->prepareLinks('lb_header_links');

        $items = [];
        foreach ($links as $i => $link) {
            $items[] = [
                'type' => 'icon',
                'mobile' => $link['lb_header_links_mobile'],
                'icon' => ['name' => $link['lb_header_links_icon']],
                'href' => $link['lb_header_links_link']
            ];
        }

        return $items;
    }

    public function getLShopLinks()
    {
        $links = $this->prepareLinks('lb_menu_shop_links');

        $items[] = ['type' => 'separator'];

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
                'variants' => ['type-1', 'type-1-secondary'],
            ],
        ];
    }

    public function getApiKey($service)
    {
        return $this->getOption($service);
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
