
<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class CarouselBannerAlternate extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        if( have_rows('lb_block_carousel_banner_alternate') ) {
            $slides = [];
            while( have_rows('lb_block_carousel_banner_alternate') ) : the_row();
                $slides[] = [
                    'images' => [
                        'original' => get_sub_field('lb_block_banner_alternate_img'),
                        'large' => get_sub_field('lb_block_banner_alternate_img'),
                        'medium' => get_sub_field('lb_block_banner_alternate_img'),
                        'small' => get_sub_field('lb_block_banner_alternate_img')
                    ],
                    'noContainer' => true,
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_infobox_tagline'),
                        'title' => get_sub_field('lb_block_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_infobox_paragraph'),
                        'cta' => array_merge( get_sub_field('lb_block_infobox_btn'),['variants' => [get_sub_field('lb_block_infobox_btn_variants')]])
                    ],
                    'variants' => [get_sub_field('lb_block_banner_alternate_variants_lr'),get_sub_field('lb_block_banner_alternate_variants_hcb')],
                ];
            endwhile;
        }
        
        $payload= [
            'slides' => $slides
        
        ];
        // $this->context['data'] = array_merge($this->context['data'],$infobox);
        $this->setContext($payload);        
    }
}
