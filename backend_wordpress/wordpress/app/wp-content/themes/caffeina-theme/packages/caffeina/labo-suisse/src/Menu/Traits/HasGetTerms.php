<?php

namespace Caffeina\LaboSuisse\Menu\Traits;

trait HasGetTerms
{
    protected function getTerms($taxonomy, $args = [])
    {
        $args['taxonomy'] = $taxonomy;
        $args['hide_empty'] = false;

        return get_terms($args);
    }
}
