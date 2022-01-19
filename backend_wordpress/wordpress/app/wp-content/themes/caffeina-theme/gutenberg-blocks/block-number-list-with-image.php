<?php
require_once(__DIR__.'/classes/numberListImage.php');
use gutenbergBlocks\BaseBlock;
$block_numbers = new NumberListImage($block, null);
$block_numbers->render();




