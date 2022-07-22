<?php

namespace Caffeina\LaboSuisse\Blocks;

class Hero extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $sizes = [
            // 'lg' => 'lg',
            // 'md' => 'md-hero',
            // 'sm' => 'md-hero',
            // 'xs' => 'md-hero'
            'lg' => 'lg',
            'md' => 'lg',
            'sm' => 'lg',
            'xs' => 'lg'
        ];

        $payload = [
            'images' => lb_get_images(get_field('lb_block_hero_img'), $sizes),
            'infoboxPosX' => get_field('lb_block_hero_infoboxposx'),
            'infoboxPosY' => get_field('lb_block_hero_infoboxposy'),
            'container' => get_field('lb_block_hero_container'),
            'whiteText' => get_field('lb_block_hero_text_white'),
            'animationType' => get_field('lb_block_hero_animation_type'),
            'variants' => [get_field('lb_block_hero_variants')],
        ];

        // $this->context['data'] = array_merge($this->context['data'],$infobox);
        $this->setContext($payload);
        $this->addInfobox();
        $this->linkToPreload($payload['images']);
    }

    public function linkToPreload($image) {
        
        if (!empty($image)) {

            $preload_image = $image['original'];
    
            add_action('wp_head', function() use ($preload_image)  {
                ?>
                <link rel="preload" as="image" href="<?php echo $preload_image; ?>">
                <?php
            });
        }
    }
}
