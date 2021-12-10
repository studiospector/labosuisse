<?php
require_once(__DIR__.'/basePage.php');
use pages\basePage;
class macro extends basePage  {
 
    public function __construct($name, $term){
        parent::__construct('macro',$term);
        $payload =  get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id
        ));
    
        $this->setContext($payload);
        //$this->macro->render();
    }   
}




