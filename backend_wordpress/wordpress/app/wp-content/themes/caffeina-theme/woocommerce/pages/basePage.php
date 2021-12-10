<?php

namespace Pages;

class basePage {
    private $context;
    public $name;
    public function __construct($name, $term){
        $this->name = $name;

        $this->context = [
            'level' => $this->name,
            'data' => [
                'termName' => $term->name,
            ]
        ];
    }

  public function setContext($payload){
    $this->context['data'] = array_merge($this->context['data'],$payload);


  }
    public function render(){
    
        \Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $this->context);
    
    }

    
}
