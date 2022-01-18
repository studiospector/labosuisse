<?php

namespace gutenbergBlocks;

class BaseBlock {
  public $id ;
  public $name;
  public $className;
  public $payload;
  protected $block;
  private $context;
  private $field_names = ['block_id', 'block_className'];

  public function __construct($block = null, $name = null)
  {
    if ($block != null){
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
    
       
    }else{
        $this->name = $name;
        $this->id = 0;
        $this->className = "";
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

  public function addInfobox(&$context = null){
      //creare cta

    $infobox = [
            'infobox' => [
                'tagline' => get_field('lb_block_infobox_tagline'),
                'title' => get_field('lb_block_infobox_title'),
                'subtitle' => get_field('lb_block_infobox_subtitle'),
                'paragraph' => get_field('lb_block_infobox_paragraph'),
                'cta' => array_merge( is_array(get_field('lb_block_infobox_btn')) ? get_field('lb_block_infobox_btn') : [] ,['variants' => [get_field('lb_block_infobox_btn_variants')]])
            ]
    ];

    if (!is_null($context) ){

       $context = array_merge($context,$infobox);
    }else{
        $this->context['data'] = array_merge( $this->context['data'],$infobox);
        $this->payload = array_merge( $this->payload,$infobox);
    }
    // echo '<pre>';
    // var_dump( $infobox );
    // die;


  }

  public function setContext($payload){
      $this->payload = $payload;
      $this->context['data'] = array_merge($this->context['data'],$payload);


  }
  public function render(){
//       echo "<pre>";
// var_dump($this->context);die;
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
