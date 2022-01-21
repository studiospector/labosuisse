<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class Infobox extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $this->addInfobox();
        $this->render();
        
    }
}


