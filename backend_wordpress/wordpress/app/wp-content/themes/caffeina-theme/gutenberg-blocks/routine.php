<?php
require_once(__DIR__.'/classes/routine.php');
use gutenbergBlocks\BaseBlock;
$block_routine = new Routine($block,"block-routine");
$block_routine->render();
