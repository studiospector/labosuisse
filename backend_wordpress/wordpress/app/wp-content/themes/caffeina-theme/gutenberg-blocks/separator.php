<?php

require_once(__DIR__ . '/baseBlock.php');

use gutenbergBlocks\BaseBlock;

$separator = new BaseBlock($block);

$payload = [
    'variants' => [get_field('lb_block_separator_variants')],
];

$separator->setContext($payload);

$separator->render();
