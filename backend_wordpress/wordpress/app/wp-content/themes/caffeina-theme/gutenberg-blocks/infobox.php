<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_launch_two_images = new BaseBlock($block,$twig);
$block_launch_two_images->addInfobox();
$block_launch_two_images->render();
