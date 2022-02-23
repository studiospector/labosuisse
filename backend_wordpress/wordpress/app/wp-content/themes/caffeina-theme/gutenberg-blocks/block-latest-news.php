<?php

use Caffeina\LaboSwiss\Blocks\LatestNews;

$latestNewsBlock = (new LatestNews($block, 'cards-grid'))
    ->render();
