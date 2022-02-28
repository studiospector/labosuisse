<?php

use Caffeina\LaboSuisse\Blocks\LatestNews;

$latestNewsBlock = (new LatestNews($block, 'cards-grid'))
    ->render();
