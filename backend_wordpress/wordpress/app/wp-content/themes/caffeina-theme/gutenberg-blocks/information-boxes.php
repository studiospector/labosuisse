<?php

use Caffeina\LaboSuisse\Blocks\InformationBoxes;

$blockInformationBoxes = (new InformationBoxes($block, 'information-boxes', null))
    ->render();
