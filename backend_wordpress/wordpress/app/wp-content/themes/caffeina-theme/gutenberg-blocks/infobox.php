<?php
require_once(__DIR__.'/classes/infobox.php');
use gutenbergBlocks\BaseBlock;
$block_infobox = new Infobox($block,null);

$infobox = [
        'tagline' => get_field('lb_block_infobox_tagline'),
        'title' => get_field('lb_block_infobox_title'),
        'subtitle' => get_field('lb_block_infobox_subtitle'),
        'paragraph' => get_field('lb_block_infobox_paragraph'),
      //  'cta' => array_merge( get_field('lb_block_infobox_btn') == "" ? [] : [get_field('lb_block_infobox_btn')] ,['variants' => [get_field('lb_block_infobox_btn_variants')]])
];
if (get_field('lb_block_infobox_btn') != "") {
$infobox['cta'] = array_merge (  (array)get_field('lb_block_infobox_btn') ,['variants' => [get_field('lb_block_infobox_btn_variants')]]);

}
$block_infobox->setContext($infobox);
$block_infobox->render();
