<?php
require_once(__DIR__.'/classes/infobox.php');
use gutenbergBlocks\BaseBlock;
$block_infobox = new Infobox($block,$twig);
$block_infobox->addInfobox();
$block_infobox->render();
