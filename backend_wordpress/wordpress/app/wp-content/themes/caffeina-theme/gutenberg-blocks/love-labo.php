

<?php
require_once(__DIR__.'/classes/loveLabo.php');
use gutenbergBlocks\BaseBlock;
$block_love_labo = new LoveLabo($block, null);
$block_love_labo->render();
