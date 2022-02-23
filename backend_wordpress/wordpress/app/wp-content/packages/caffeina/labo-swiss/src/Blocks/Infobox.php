<?php

namespace Caffeina\LaboSwiss\Blocks;

class Infobox extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        $this->addInfobox();
        $this->render();
    }
}
