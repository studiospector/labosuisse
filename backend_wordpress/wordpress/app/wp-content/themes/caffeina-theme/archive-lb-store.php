<?php


use Caffeina\LaboSuisse\Option\Option;
use Timber\Timber;

$options = (new Option())->getStoresOptions();

$context = [
    'title' => $options['title'],
    'description' => $options['description'],
    'infobox' => [
        'infobox' => [
            'image' => $options['infobox']['image'],
            'tagline' => $options['infobox']['tagline'],
            'title' => $options['infobox']['title'],
            'subtitle' => $options['infobox']['subtitle'],
            'paragraph' => $options['infobox']['paragraph'],
            'cta' => $options['infobox']['cta'],
        ]
    ]
];

//Timber::render('@PathViews/', $context);
