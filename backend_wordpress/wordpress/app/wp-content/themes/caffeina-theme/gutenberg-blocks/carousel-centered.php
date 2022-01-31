

<?php
require_once(__DIR__.'/classes/carouselCentered.php');
use gutenbergBlocks\BaseBlock;
$block_carousel_centered = new CarouselCentered($block, null);

$block_carousel_centered->render();




