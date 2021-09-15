<?php

$data = 'Malini is not active';
if (function_exists('malini')) {
    $data = malini_post()
      ->decorate('post', [
        'filter' => ['title'],
      ])
      ->toArray();
}

jsonResponse('index', $data);
