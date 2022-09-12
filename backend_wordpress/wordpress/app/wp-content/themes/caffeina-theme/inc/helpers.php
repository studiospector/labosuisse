<?php

use Caffeina\LaboSuisse\Menu\Menu;
use Caffeina\LaboSuisse\Menu\MenuFooter;
use Caffeina\LaboSuisse\Option\Option;

/**
 * Get Images array
 */
function lb_get_images($id, $sizes = [])
{
    $data = null;

    if (!empty($id)) {
        $data = [
            'alt' => get_the_title($id),
            'original' => wp_get_attachment_url($id),
            'lg' => (empty($sizes) && empty($sizes['lg'])) ? wp_get_attachment_image_src($id, 'lb-img-size-lg')[0] : wp_get_attachment_image_src($id, "lb-img-size-{$sizes['lg']}")[0],
            'md' => (empty($sizes) && empty($sizes['md'])) ? wp_get_attachment_image_src($id, 'lb-img-size-md')[0] : wp_get_attachment_image_src($id, "lb-img-size-{$sizes['md']}")[0],
            'sm' => (empty($sizes) && empty($sizes['sm'])) ? wp_get_attachment_image_src($id, 'lb-img-size-sm')[0] : wp_get_attachment_image_src($id, "lb-img-size-{$sizes['sm']}")[0],
            'xs' => (empty($sizes) && empty($sizes['xs'])) ? wp_get_attachment_image_src($id, 'lb-img-size-xs')[0] : wp_get_attachment_image_src($id, "lb-img-size-{$sizes['xs']}")[0],
        ];
    }

    return $data;
}

/**
 * Get all Brands
 */
function lb_get_brands($search = [])
{
    $args = [
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
    ];
    $args = array_merge($args, $search);

    $brands = get_terms($args);

    $i = 0;
    foreach ($brands as $term) {
        if ($term->parent != 0) {
            unset($brands[$i]);
        }
        $i++;
    }

    return $brands;
}

/**
 * Get all "Linee di prodotto"
 */
function lb_get_brands_product_lines()
{
    $product_lines = get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
    ));

    $i = 0;
    foreach ($product_lines as $term) {
        if ($term->parent == 0) {
            unset($product_lines[$i]);
        }
        $i++;
    }

    return $product_lines;
}

/**
 * Get taxonomy term level
 */
function get_category_parents_custom($category_id, $tax)
{
    $args = array(
        'separator' => ',',
        'link'      => false,
        'format'    => 'slug',
    );

    $parent_terms = get_term_parents_list($category_id, $tax, $args);

    return substr_count($parent_terms, ',');
}


function lb_get_job_location_options()
{
    return lb_get_job_options('lb-job-location');
}

function lb_get_job_department_options()
{
    return lb_get_job_options('lb-job-department');
}

function lb_get_job_options($type)
{
    $items = get_terms([
        'taxonomy' => $type,
        'hide_empty' => false,
    ]);

    $options = [];

    foreach ($items as $item) {
        $options[] = [
            'value' => $item->term_id,
            'label' => $item->name,
        ];
    }

    return $options;
}

function get_job_location()
{
    $isHeadquarter = false;
    $job_location_links = null;

    $job_location_terms = get_the_terms(get_the_ID(), 'lb-job-location');

    if ($job_location_terms && !is_wp_error($job_location_terms)) {
        $job_location_draught_links = [];

        foreach ($job_location_terms as $term) {
            if (get_field('lb_job_location_headquarter', $term)) {
                $isHeadquarter = true;
            }

            $job_location_draught_links[] = $term->name;
        }

        $job_location_links = join(", ", $job_location_draught_links);
    }

    return [
        'jobLocationLinks' => esc_html($job_location_links),
        'isHeadquarter' => $isHeadquarter
    ];
}

function get_all_macro()
{
    return get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => null,
        'exclude' => get_option('default_product_cat')
    ]);
}

function get_all_area($macro)
{
    return get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => $macro->term_id
    ]);
}

function get_all_needs($area)
{
    return get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => $area->term_id
    ]);
}

function getBaseHomeUrl()
{
    global $wp;
    $current_url = home_url($wp->request);

    $pos = strpos($current_url, '/page');

    if ($pos) {
        $current_url = substr($current_url, 0, $pos);
    }

    return $current_url;
}

/**
 * Get posts archive years
 */
function lb_get_post_typologies()
{
    $items = [];
    $typologies = get_terms([
        'taxonomy' => 'lb-post-typology',
        'hide_empty' => false,
    ]);

    foreach ($typologies as $typology) {
        $items[] = [
            'label' => $typology->name,
            'value' => $typology->term_id,
        ];
    }

    return $items;
}

/**
 * Get posts archive years
 */
function lb_get_posts_archive_years()
{
    $years = array();
    $years_args = array(
        'type' => 'yearly',
        'format' => 'custom',
        'before' => '',
        'after' => '|',
        'echo' => false,
        'post_type' => 'post',
    );

    // Get Years
    $years_content = wp_get_archives($years_args);
    if (!empty($years_content)) {
        $years_arr = explode('|', $years_content);
        $years_arr = array_filter($years_arr, function ($item) {
            return trim($item) !== '';
        }); // Remove empty whitespace item from array

        foreach ($years_arr as $year_item) {
            $year_row = trim($year_item);
            preg_match('/href=["\']?([^"\'>]+)["\']>(.+)<\/a>/', $year_row, $year_vars);

            if (!empty($year_vars)) {
                $years[] = array(
                    'label' => $year_vars[2],
                    'value' => $year_vars[2]
                );
            }
        }
    }

    return $years;
}

/**
 * Get Posts count by taxonomy
 */
function get_posts_count_by_taxonomy($post_type, $taxonomy, $terms)
{
    $term_posts = get_posts(array(
        'post_type' => $post_type,
        'numberposts' => -1,
        'suppress_filters' => false,
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $terms
            )
        )
    ));

    return count($term_posts);
}

/**
 * Assign global $product object in Timber
 */
function timber_set_product($post)
{
    global $product;
    $product = isset($post->product) ? $post->product : wc_get_product($post->ID);
}

/**
 * Get current language
 */
function lb_get_current_lang()
{
    global $sitepress;

    $current_lang = $sitepress->get_current_language();

    return $current_lang;
}

/**
 * Custom pagination
 */
function lb_pagination()
{
    $pagination_html = null;

    $pagination = get_the_posts_pagination([
        'screen_reader_text' => '&nbsp;',
        'mid_size' => 2,
        'prev_text' => '<div class="button button-tertiary">' . Timber::compile('@PathViews/components/icon.twig', ['name' => 'arrow-left']) . '</div>',
        'next_text' => '<div class="button button-tertiary">' . Timber::compile('@PathViews/components/icon.twig', ['name' => 'arrow-right']) . '</div>',
        'before_page_number' => '<div class="button button-secondary">',
        'after_page_number'  => '</div>'
    ]);

    if ($pagination) {
        $pagination_html = "<div class=\"lb-pagination container\"><hr class=\"lb-separator lb-separator--medium\" data-variant=\"medium\">$pagination</div>";
    }

    return $pagination_html;
}

/**
 * Get header and menu
 */
function lb_header()
{
    $lang_selector = do_shortcode('[wpml_language_selector_widget]');

    return array(
        'language_selector' => (!empty($lang_selector)) ? true : false,
        'header_links' => ['items' => (new Option())->getHeaderLinks()],
        'mobile_search' => [
            'type' => 'search',
            'id' => 'lb-search-header-mobile',
            'name' => 'search',
            'label' => __('Cerca un prodotto, una linea...', 'labo-suisse-theme'),
            'disabled' => false,
            'required' => false,
            'buttonTypeNext' => 'button',
            'variants' => ['secondary'],
        ],
        'menu_desktop' => ['items' => Menu::desktop()],
        'menu_mobile' => ['items' => Menu::mobile()],
    );
}

/**
 * Get footer and prefooter
 */
function lb_footer()
{
    return MenuFooter::get();
}

/**
 * Newsletter nav
 */
function lb_newsletter_nav()
{
    $footer_newsletter_options = get_field('lb_footer_newsletter', 'option');

    return [
        'id' => 'lb-newsletter-nav',
        'title' => __('Newsletter', 'labo-suisse-theme'),
        'data' => [
            [
                'type' => 'text',
                'data' => [
                    'title' => __('Iscriviti alla newsletter', 'labo-suisse-theme'),
                    'text' => __('Ricevi aggiornamenti e promozioni direttamente sulla tua mail.', 'labo-suisse-theme'),
                ]
            ],
            [
                'type' => 'html',
                'data' => (isset($footer_newsletter_options['lb_footer_newsletter_shortcode']))
                    ? do_shortcode($footer_newsletter_options['lb_footer_newsletter_shortcode'])
                    : null
            ]
        ]
    ];
}

/**
 * Cart nav
 */
function lb_async_cart_nav() {
    return [
        'id' => 'lb-offsetnav-async-cart',
        'title' => __('Carrello', 'labo-suisse-theme'),
        'data' => [
            [
                'type' => 'html',
                'data' => Timber::compile('@PathViews/components/offset-nav/templates/async-cart-content.twig'),
            ]
        ]
    ];
}

/**
 * Check if current user has role
 */
function lb_user_has_role($role)
{
    $user = get_userdata(get_current_user_id());

    if (!$user || !$user->roles) {
        return false;
    }

    if (is_array($role)) {
        return array_intersect($role, (array)$user->roles) ? true : false;
    }

    return in_array($role, (array)$user->roles);
}

/**
 * Custom recursive multidimensional array search
 */
function lb_recursive_array_search($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        $current_key = $key;
        if (
            $needle === $value
            or (is_array($value)
                && lb_recursive_array_search($needle, $value) !== false
            )
        ) {
            return $current_key;
        }
    }
    return false;
}

/**
 * Get unique ID
 */
function lb_get_unique_id($prefix = '')
{
    static $id_counter = 0;

    if (function_exists('wp_unique_id'))
        return wp_unique_id($prefix);

    return $prefix . (string) ++$id_counter;
}

/**
 * Change key of array from old to new value
 */
function lb_array_change_key( $array, $old_key, $new_key ) {

    if( ! array_key_exists( $old_key, $array ) )
        return $array;

    $keys = array_keys( $array );
    $keys[ array_search( $old_key, $keys ) ] = $new_key;

    return array_combine( $keys, $array );
}

/**
 * Move array element and change key
 */
function lb_move_array_element(&$array, $a, $b, $key)
{
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);

    $array = lb_array_change_key($array, 0, $key);

    return $array;
}
