<?php

$context = [
    'title' => get_the_title(),
    'content' => apply_filters( 'the_content', get_the_content() ),
];

echo $twig->render('page.twig', $context);