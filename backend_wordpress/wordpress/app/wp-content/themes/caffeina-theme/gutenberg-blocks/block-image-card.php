<?php
require_once(__DIR__.'/classes/imageCard.php');
use gutenbergBlocks\BaseBlock;
$block_image_card = new ImageCard($block,'block-image-card');
// echo "<pre>";
// var_dump($block_image_card->context);
//  die;
$block_image_card->render();
