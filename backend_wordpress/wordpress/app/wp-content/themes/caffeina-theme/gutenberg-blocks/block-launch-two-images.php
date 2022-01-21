<?php
require_once(__DIR__.'/classes/launchTwoImages.php');
use gutenbergBlocks\BaseBlock;
$block_launch_two_images = new LaunchTwoImages($block, null);


$block_launch_two_images->render();
