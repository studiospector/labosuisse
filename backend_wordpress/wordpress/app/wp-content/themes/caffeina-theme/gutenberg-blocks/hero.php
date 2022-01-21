

<?php
require_once(__DIR__.'/classes/hero.php');
use gutenbergBlocks\BaseBlock;
$block_hero = new Hero($block, null);
$block_hero->render();
