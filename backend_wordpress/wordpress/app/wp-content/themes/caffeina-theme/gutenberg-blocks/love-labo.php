

<?php
require_once(__DIR__.'/classes/loveLabo.php');
use gutenbergBlocks\BaseBlock;
$block_love_labo = new LoveLabo($block, "block-love-labo");
$block_love_labo->render();
