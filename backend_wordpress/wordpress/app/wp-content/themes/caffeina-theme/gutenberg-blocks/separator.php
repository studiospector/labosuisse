<?php

use Caffeina\LaboSwiss\Blocks\BaseBlock as LaboSwissBlocksBaseBlock;

$separator = new LaboSwissBlocksBaseBlock($block);

$separator->setContext([
    'variants' => [get_field('lb_block_separator_variants')],
]);

$separator->render();
