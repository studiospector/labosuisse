<?php
require_once(__DIR__.'/classes/cardsGrid.php');
use gutenbergBlocks\BaseBlock;
$block_routine = new cardsGrid($block,"cards-grid");
$block_routine->render();
