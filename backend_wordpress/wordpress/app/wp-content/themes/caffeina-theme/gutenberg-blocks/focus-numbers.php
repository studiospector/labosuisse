

<?php
require_once(__DIR__.'/classes/focusNumbers.php');
use gutenbergBlocks\BaseBlock;
$block_focus_numbers = new FocusNumbers($block, null);

$block_focus_numbers->render();




