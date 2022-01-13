

<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_love_labo = new BaseBlock($block);
$items = [];
for ($i = 1; $i <= 6; $i++) {
    $items[] = [
            'images' =>[
                'original' => get_field('lb_block_love_labo_img_'.$i),
                'large' => get_field('lb_block_love_labo_img_'.$i),
                'medium' => get_field('lb_block_love_labo_img_'.$i),
                'small' => get_field('lb_block_love_labo_img_'.$i)
            ],
            'text' => get_field('lb_block_love_labo_text_'.$i)
            
    ];
}


$payload = [
    'items' => $items,
    'variants' => get_field('lb_block_love_labo_variants')
];
// $this->context['data'] = array_merge($this->context['data'],$infobox);
$block_love_labo->setContext($payload);
$block_love_labo->addInfobox();
$block_love_labo->render();
