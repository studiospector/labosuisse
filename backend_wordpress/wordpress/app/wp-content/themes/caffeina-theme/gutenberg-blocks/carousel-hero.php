

<?php
require_once(__DIR__.'/classes/carouselHero.php');
use gutenbergBlocks\BaseBlock;
$block_carousel_hero = new CarouselHero($block, null);

$block_carousel_hero->render();




