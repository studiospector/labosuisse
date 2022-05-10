<?php

header("HTTP/1.0 404 Not Found");

$context = [
    'infobox' => [
        'tagline' => __('Errore 404', 'labo-suisse-theme'),
        'subtitle' => __('Ci dispiace, non riusciamo a trovare<br>la pagina che stai cercando. ', 'labo-suisse-theme'),
        'paragraph' => __('Verifica l’indirizzo e riprova, oppure clicca sul pulsante<br>sotto per continuare a navigare sul nostro sito.', 'labo-suisse-theme'),
        'cta' =>[
            'title' => __('Torna alla Homepage', 'labo-suisse-theme'),
            'url' => home_url(),
            'variants' => ['primary']
        ],
    ]
];

Timber::render('@PathViews/404.twig', $context);
