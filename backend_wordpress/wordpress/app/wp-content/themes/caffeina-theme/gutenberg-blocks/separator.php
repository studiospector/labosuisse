<?php

use Caffeina\LaboSuisse\Blocks\BaseBlock as LaboSuisseBlocksBaseBlock;

$separator = new LaboSuisseBlocksBaseBlock($block);

$with_line = get_field('lb_block_separator_line') ? 'line' : null;
$variants = get_field('lb_block_separator_variants');

$separator->setContext([
    'container' => get_field('lb_block_separator_container'),
    'variants' => $with_line ? [$variants, $with_line] : [$variants],
]);

$separator->render();
