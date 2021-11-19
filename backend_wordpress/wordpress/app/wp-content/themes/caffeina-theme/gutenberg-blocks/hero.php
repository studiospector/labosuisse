

<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_hero = new BaseBlock($block);

$payload = [
    'images' => [
        'original' => get_sub_field('lb_block_hero_img'),
        'large' => get_sub_field('lb_block_hero_img'),
        'medium' => get_sub_field('lb_block_hero_img'),
        'small' => get_sub_field('lb_block_hero_img')
    ],
    'infoboxPosX' => get_sub_field('lb_block_hero_infoboxposx'),
    'infoboxPosY' => get_sub_field('lb_block_hero_infoboxposy'),
    'container' => get_sub_field('lb_block_hero_container'),
    'variants' => get_sub_field('lb_block_hero_variants'),

];

// $this->context['data'] = array_merge($this->context['data'],$infobox);
$block_hero->setContext($payload);
$block_hero->addInfobox();
$block_hero->render();




