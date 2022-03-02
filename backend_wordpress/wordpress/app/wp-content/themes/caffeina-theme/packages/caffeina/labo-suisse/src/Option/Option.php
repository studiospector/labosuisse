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
                'href' => get_permalink($link['lb_header_links_link']->ID)
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
                'href' => get_permalink($link['lb_menu_shop_links_link']->ID)
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
                'cta' => [
                    'url' => $this->getOption('lb_faq_infobox_cta_link_option'),
                    'title' => $this->getOption('lb_faq_infobox_cta_label_option'),
                ]
            ]
        ];
    }

    public function geJobsOptions()
    {
        return [
            'title' => $this->getOption('lb_jobs_title_option'),
            'description' => $this->getOption('lb_jobs_description_option'),
            'infobox' => [
                'title' => $this->getOption('lb_jobs_infobox_subtitle_option'),
                'paragraph' => $this->getOption('lb_jobs_infobox_paragraph_option'),
                'cta' => [
                    'url' => $this->getOption('lb_jobs_infobox_cta_link_option'),
                    'title' => $this->getOption('lb_jobs_infobox_cta_label_option'),
                ]
            ]
        ];
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
