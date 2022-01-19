<?php
require_once(__DIR__.'/classes/imageCard.php');
use gutenbergBlocks\BaseBlock;
$block_image_card = new ImageCard($block,null);
$block_image_card->render();
