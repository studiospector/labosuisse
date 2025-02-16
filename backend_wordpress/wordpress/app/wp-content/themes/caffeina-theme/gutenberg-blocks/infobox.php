<?php

use Caffeina\LaboSuisse\Blocks\Infobox;

$block_infobox = new Infobox($block, null);

$infobox = [
    'tagline' => get_field('lb_block_infobox_tagline'),
    'title' => get_field('lb_block_infobox_title'),
    'subtitle' => get_field('lb_block_infobox_subtitle'),
    'paragraph' => get_field('lb_block_infobox_paragraph'),
    'textAlign' => get_field('lb_block_infobox_text_align'),
    'container_width' => get_field('lb_block_infobox_width') ? get_field('lb_block_infobox_width') : 'full',
    //  'cta' => array_merge( get_field('lb_block_infobox_btn') == "" ? [] : [get_field('lb_block_infobox_btn')] ,['variants' => [get_field('lb_block_infobox_btn_variants')]])
];

if (get_field('lb_block_infobox_btn') != "") {
    $infobox['cta'] = array_merge((array)get_field('lb_block_infobox_btn'), ['variants' => [get_field('lb_block_infobox_btn_variants')]]);
}

$block_infobox->setContext($infobox);
$block_infobox->render();
