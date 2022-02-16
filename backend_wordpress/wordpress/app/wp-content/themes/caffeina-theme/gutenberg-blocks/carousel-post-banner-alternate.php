

<?php
require_once(__DIR__ . '/classes/carouselPostBannerAlternate.php');

use gutenbergBlocks\BaseBlock;

$block_carousel_post_banner_alternate = new CarouselPostBannerAlternate($block, 'carousel-banner-alternate');

$block_carousel_post_banner_alternate->render();
