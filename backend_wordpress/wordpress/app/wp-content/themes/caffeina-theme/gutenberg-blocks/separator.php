<?php

use Caffeina\LaboSuisse\Blocks\BaseBlock as LaboSuisseBlocksBaseBlock;

$separator = new LaboSuisseBlocksBaseBlock($block);

$separator->setContext([
    'variants' => [get_field('lb_block_separator_variants')],
]);

$separator->render();
