<?php

namespace Caffeina\LaboSwiss\Menu;

abstract class Menu
{
    protected function getTerms($taxonomy, $args = [])
    {
        $args['taxonomy'] = $taxonomy;
        $args['hide_empty'] = false;

        return get_terms($args);
    }
}
