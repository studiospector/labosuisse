<?php

$context = [
    'title' => get_the_title(),
    'info' => [
        [
            'label' =>  __('Location:', 'labo-suisse-theme'),
            'value' => 'Non specificata',
        ],
        [
            'label' =>  __('Tipologia di contratto:', 'labo-suisse-theme'),
            'value' => get_field('lb_job_contract_typology'),
        ],
        [
            'label' =>  __('Descrizione del ruolo:', 'labo-suisse-theme'),
            'value' => get_the_excerpt(),
        ],
    ],
    'company' => [
        'title' => __("L'azienda", 'labo-suisse-theme'),
        'content' => 'Labo International è una multinazionale qualificata e operante nel settore della cosmetica ad alto livello in oltre 30 Paesi.<br><br>Fondata a Basilea nel 1986, da più di 30 anni è impegnata nella ricerca e nello sviluppo di prodotti dermo-cosmetici innovativi e differenzianti per funzione e promessa. Ad oggi i prodotti Labo sono coperti da 28 Brevetti Svizzeri e da 1 Brevetto Europeo (attualmente per un totale di 28 brevetti e 1 brevetto in fase di concessione). Labo detiene la proprietà di 70 marchi, i più importanti dei quali sono registrati in tutto il mondo. Labo è oggi presente in 36 Paesi con distributori qualificati, scelti con l’obiettivo di porre basi solide per un veloce e costante sviluppo commerciale. La mission di Labo è quella di conquistare i migliori punti vendita nel mondo, fidelizzando i consumatori ai propri trattamenti dermo-cosmetici di alta gamma e dal forte valore in termini di innovazione ed unicità.'
    ],
    'requirements' => [
        'title' => __('Il candidato', 'labo-suisse-theme'),
        'content' => apply_filters( 'the_content', get_the_content() ),
    ],
    'application' => [
        'title' => __('Modulo di candidatura', 'labo-suisse-theme'),
        'content' => "Invitiamo ad inviare il proprio dettagliato curriculum vitae comprensivo di foto indicando con precisione, nell'apposito menu, la posizione di riferimento per la quale si desidera inoltrare la propria candidatura (scegliere la voce 'autocandidatura' qualora ci si voglia proporre per posizioni lavorative non in elenco). La selezione sarà curata direttamente dall’Azienda.<br><br>I presenti annunci sono rivolti ad entrambi i sessi, ai sensi delle leggi 903/77 e 125/91, e a persone di tutte le nazionalità, ai sensi dei decreti legislativi 215/03 e 216/03. ",
        'shortcode' => get_field('lb_jobs_form_application_shortcode', 'option'),
    ]
];

Timber::render('@PathViews/single-lb-job.twig', $context);
