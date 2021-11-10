<?php

namespace gutenbergBlocks;

class BaseBlock {
  public $id;
  public $name;
  public $className;
  protected $block;
  private $context;
  private $field_names = ['block_id', 'block_className'];

  public function __construct($block)
  {
    $this->block = $block;
    $this->name = str_replace("acf/lb-","",$this->block['name']);
    $this->id = $this->name . $block['id'];
    // $this->id = "hero-" . $this->block['id'];
    if( !empty($this->block['anchor']) ) {
        $id = $this->block['anchor'];
    }
    //?
    $className = $this->name;
    // $this->className = 'hero';
    if( !empty($this->block['className']) ) {
        $this->className .= ' ' . $this->block['className'];
    }
    if( !empty($this->block['align']) ) {
        $this->className.= ' align' . $this->block['align'];
    }

    $this->context = [
        'block' => $this->name,
        'data' => [
            'conf' => [
                'id' => esc_attr($this->id),
                'classes' => esc_attr($this->className)
            ],
        ]
    ];
  }

  public function addInfobox(){
      $infobox = [
            'infobox' => [
                'side' => get_field('lb_block_infobox_side'),
                'tagline' => get_field('lb_block_infobox_tagline'),
                'title' => get_field('lb_block_infobox_title'),
                'subtitle' => get_field('lb_block_infobox_subtitle'),
                'paragraph' => get_field('lb_block_infobox_paragraph'),
                'button' => get_field('lb_block_infobox_btn'),
                'buttonVariants' => get_field('lb_block_infobox_btn_variants')
            ]
        ];

     $this->context['data'] = array_merge($this->context['data'],$infobox);
     // echo "<pre>";
    // var_dump( $this->context);
    //die;
  }

  public function setContext($payload){
    $this->context['data'] = array_merge($this->context['data'],$payload);
//     echo "<pre>";
// var_dump($this->context);
// die;

  }
  public function render(){
    //   echo "<pre>";
    //   var_dump( $this->context);
    //   die;
   // Preview in editor
    if ( isset($this->block['data']['is_preview']) && $this->block['data']['is_preview'] == true ) {
        \Timber::render('@PathViews/gutenberg-preview.twig', [
            'base_url' => get_site_url(),
            'name' => $this->name,
            'img' => 'block-two-images',
            'ext' => 'png',
        ]);
        return;
    }

    \Timber::render('@PathViews/components/base/gutenberg-block-switcher.twig', $this->context);

  }



}
