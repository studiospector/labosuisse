<?php
require_once(__DIR__ . '/classes/latestNews.php');

$block_banner = new LatestNews($block, 'cards-grid');
$block_banner->render();
