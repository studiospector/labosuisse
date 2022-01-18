<?php
require_once(__DIR__.'/classes/launchTwoCards.php');
use gutenbergBlocks\BaseBlock;
$block_launch_two_cards = new LaunchTwoCards($block, null);
$block_launch_two_cards->render();
