

<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_love_labo = new BaseBlock($block);
$items = [];
for ($i = 1; $i <= 6; $i++) {
    $items[] = [
            'original' => get_sub_field('lb_block_love_labo_img_'.$i),
            'large' => get_sub_field('lb_block_love_labo_img_'.$i),
            'medium' => get_sub_field('lb_block_love_labo_img_'.$i),
            'small' => get_sub_field('lb_block_love_labo_img_'.$i)
    ];
}


$payload = [
    'items' => $items

];
// $this->context['data'] = array_merge($this->context['data'],$infobox);
$block_love_labo->setContext($payload);
$block_love_labo->addInfobox();
$block_love_labo->render();




