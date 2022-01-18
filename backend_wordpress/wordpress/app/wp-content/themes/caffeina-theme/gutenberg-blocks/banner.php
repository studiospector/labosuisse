<?php
require_once(__DIR__.'/classes/banner.php');
use gutenbergBlocks\BaseBlock;
$block_banner = new Banner($block,null);
$block_banner->render();
