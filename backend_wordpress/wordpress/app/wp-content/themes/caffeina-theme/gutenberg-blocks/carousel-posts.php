<?php
require_once(__DIR__.'/classes/carouselPosts.php');

use gutenbergBlocks\BaseBlock;

$block_carousel_posts = new CarouselPosts($block, null);

$block_carousel_posts->render();
