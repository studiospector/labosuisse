<?php
require_once(__DIR__.'/classes/launchTwoCards.php');
use gutenbergBlocks\BaseBlock;
$block_launch_two_cards = new LaunchTwoCards($block, "block-two-cards");
// echo "<pre>";
// var_dump($block_launch_two_cards->context);
// //  die;
$block_launch_two_cards->render();
