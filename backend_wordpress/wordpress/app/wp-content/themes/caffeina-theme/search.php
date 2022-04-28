<?php

use Caffeina\LaboSuisse\Api\GlobalSearch\Search;

$items = (new Search())
    ->setSearch($_GET['s'])
    ->get();

