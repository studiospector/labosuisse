<?php

use Caffeina\LaboSuisse\WCProductCategoryPages\Esigenza;
use Caffeina\LaboSuisse\WCProductCategoryPages\Macro;
use Caffeina\LaboSuisse\WCProductCategoryPages\Zona;

/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}



// Current term
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
// Direct parents of current taxonomy
$level = get_category_parents_custom($term->term_id, 'product_cat');

//$context = [];

switch ($level) {
        // Macro
    case 1:
        $macro = new Macro('macro', $term);
        $macro->render();
        break;


        // Zona
    case 2:
        $zona = new Zona('zona', $term);
        $zona->render();
        break;


        // Esigenza
    case 3:

        $esigenza = new Esigenza('esigenza', $term);
        $esigenza->render();
        break;


        // Tipologia
    case 4:
        return wp_safe_redirect(
            get_search_link()
        );


        // Default
    default:
        echo "Level others";
}
