<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_infobox = new BaseBlock($block,$twig);
$block_infobox->addInfobox();
$block_infobox->render();
