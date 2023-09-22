<?php

use Caffeina\LaboSuisse\Option\Option;
use Caffeina\LaboSuisse\Setup\CustomPostType;
use Caffeina\LaboSuisse\Setup\CustomTaxonomy;
use Caffeina\LaboSuisse\Setup\Clean;
use Caffeina\LaboSuisse\Setup\Assets;
use Caffeina\LaboSuisse\Shortcodes\CookiebotDeclarationShortcode;
use Caffeina\LaboSuisse\Shortcodes\WCSignInUpShortcode;
// use Caffeina\LaboSuisse\Services\MC4WP\SettingsMC4WP;
use Caffeina\LaboSuisse\Resources\Message;

$composer_autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
        }
    );

    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = ['views'];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class ThemeSetup extends Timber\Site
{

    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        // add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter('timber/twig', array($this, 'lb_add_to_twig'));
        add_filter('timber/loader/loader', array($this, 'lb_add_to_twig_loader'));
        add_action('init', [$this, 'lb_unregister_taxonomies'], 999);
        add_action('pre_get_posts', [$this, 'lb_post_filters']);
        add_action('acf/init', [$this, 'lb_set_services_api_key']);
        add_filter('acf/settings/capability', [$this, 'lb_acf_capability']);
        add_filter('acf/settings/save_json', [$this, 'lb_acf_json_save_point']);
        add_filter('acf/settings/load_json', [$this, 'lb_acf_json_load_point']);
        add_action('wp_logout', [$this, 'lb_logout_redirect'], 999);
        add_action('block_categories_all', [$this, 'lb_gutenberg_block_categories'], 10, 2);
        add_action('admin_footer', [$this, 'lb_add_symbols_to_admin']);
        add_action('init', array($this, 'lb_manage_thumbnails'));
        add_filter('fallback_intermediate_image_sizes', array($this, 'lb_disable_pdf_thumbnails'));
        add_filter('wpseo_breadcrumb_separator', array($this, 'lb_yoast_breadcrumb_separator'), 10, 1);
        add_filter('excerpt_length', array($this, 'lb_excerpt_length'), 999);
        add_filter('wpcf7_form_tag', array($this, 'lb_parse_cf7_fields'));
        add_filter('wpseo_exclude_from_sitemap_by_post_ids', array($this, 'lb_exclude_specific_posts_from_sitemap'));
        add_filter('wpseo_sitemap_exclude_post_type', array($this, 'lb_exclude_post_types_from_sitemap'), 10, 2);
        add_action('init', [$this, 'lb_update_custom_roles']);
        add_action('init', [$this, 'lb_update_editor_role']);

        add_filter('woocommerce_enqueue_styles', '__return_empty_array', 999);

        add_filter('preview_post_link', [$this, 'lb_update_preview_post_link'], 999);
        add_filter('preview_page_link', [$this, 'lb_update_preview_post_link'], 999);

        parent::__construct();
    }

    /**
     * Add supports
     */
    public function theme_supports()
    {
        load_theme_textdomain('labo-suisse-theme', LB_DIR_PATH . '/languages');

        add_theme_support('title-tag');

        add_theme_support('post-thumbnails');
        add_post_type_support('page', 'excerpt');

        // add_theme_support('post-formats', array('aside', 'gallery'));

        add_image_size('lb-img-size-lg', 1800, 700, false);
        add_image_size('lb-img-size-md', 700, 400, false);
        add_image_size('lb-img-size-md-hero', null, 350, false);
        add_image_size('lb-img-size-sm', 400, 400, false);
        add_image_size('lb-img-size-xs', 200, 200, false);

        add_theme_support('customize-selective-refresh-widgets');

        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'script',
                'style',
            ]
        );

        // Gutenberg theme support
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');

        // Register Menus
        register_nav_menus(array(
            'lb_discover_labo' => 'Scopri Labo',
            'lb_discover_labo_footer' => 'Footer | Scopri Labo',
            'lb_support_footer' => 'Footer | Assistenza',
            'lb_impressum_footer' => 'Footer | Impressum',
        ));

        // Theme Options page
        acf_add_options_page(array(
            'page_title' => 'Impostazioni Tema - Generali',
            'menu_title' => 'Opzioni Tema',
            'menu_slug' => 'lb-theme-general-settings',
            'capability' => 'edit_posts',
            'update_button' => 'Aggiorna',
            'updated_message' => 'Impostazioni aggiornate.',
            'redirect' => false
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Impostazioni Tema - Header e Menu',
            'menu_title' => 'Header e Menu',
            'parent_slug' => 'lb-theme-general-settings',
            'update_button' => 'Aggiorna',
            'updated_message' => 'Impostazioni aggiornate.',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Impostazioni Tema - Footer e Prefooter',
            'menu_title' => 'Footer',
            'parent_slug' => 'lb-theme-general-settings',
            'update_button' => 'Aggiorna',
            'updated_message' => 'Impostazioni aggiornate.',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Impostazioni Tema - Shop',
            'menu_title' => 'Shop',
            'parent_slug' => 'lb-theme-general-settings',
            'update_button' => 'Aggiorna',
            'updated_message' => 'Impostazioni aggiornate.',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Impostazioni Tema - Messaggi',
            'menu_title' => 'Messaggi',
            'parent_slug' => 'lb-theme-general-settings',
            'update_button' => 'Aggiorna',
            'updated_message' => 'Impostazioni aggiornate.',
        ));

        /**
         * Path to our custom editor style
         * It allows you to link a custom stylesheet file to the TinyMCE editor within the post edit screen
         */
        // add_editor_style('gutenberg/editor.css');

        // Remove the core block patterns
        remove_theme_support('core-block-patterns');

        /**
         * WooCommerce
         */
        add_theme_support('woocommerce', [
            'product_grid' => ['default_columns' => 4],
            'single_image_width' => 480,
        ]);
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        /**
         * Set the maximum allowed width for any content in the theme
         * like oEmbeds and images added to posts
         */
        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1240;
        }
    }

    /**
     * This is where you add some context
     */
    // public function add_to_context( $context ) {
    // 	$context['foo']   = 'bar';
    // 	$context['stuff'] = 'I am a value set in your functions.php file';
    // 	$context['notes'] = 'These values are available everytime you call Timber::context();';
    // 	$context['menu']  = new Timber\Menu();
    // 	$context['site']  = $this;
    // 	return $context;
    // }

    /**
     * Twig setup
     */
    public function lb_add_to_twig($twig)
    {
        $template_dir = get_template_directory();
        $assets_path = get_stylesheet_directory_uri() . "/assets";

        // Add Extensions
        $twig->addExtension(new Twig\Extension\StringLoaderExtension());

        // Global Variables
        $twig->addGlobal('theme', $template_dir);
        $twig->addGlobal('assets', $assets_path);

        /**
         * Add Custom Functions
         */
        $twig->addFunction(new Timber\Twig_Function('revision_files', [$this, 'revision_files']));

        $twig->addFunction(new Timber\Twig_Function('current_url', function () {
            return Timber\URLHelper::get_current_url();
        }));

        /**
         * Add Custom Filters
         */
        $twig->addFilter(new Timber\Twig_Filter('trans', function ($value, $params = [], $lang = false) {
            if ($lang) {
                global $sitepress;
                $current_lang = $sitepress->get_current_language();
                $sitepress->switch_lang($lang);
                $value = __($value, 'labo-suisse-theme');
                $sitepress->switch_lang($current_lang);
            } else {
                $value = __($value, 'labo-suisse-theme');
            }
            if (count($params)) {
                return vsprintf($value, $params);
            } else {
                return $value;
            }
        }));

        $twig->addFilter(new Timber\Twig_Filter('is_current_url', function ($link) {
            return (Timber\URLHelper::get_current_url() == $link) ? true : false;
        }));

        return $twig;
    }

    /**
     * Twig Load setup
     */
    public function lb_add_to_twig_loader($loader)
    {
        $template_dir = get_template_directory();
        $bundle_folder = 'static';
        $bundle_path = $template_dir . "/$bundle_folder";

        // Global Paths
        $loader->addPath($bundle_path, 'static');
        $loader->addPath($bundle_path, 'bundle');

        // Namespaces
        $loader->addPath($template_dir . '/views', 'PathViews');

        return $loader;
    }

    /**
     * Twig Function
     * Get paths to static files
     */
    public static function revision_files($file, $manifest_name = 'rev-manifest.json')
    {
        $bundle_folder = 'static';
        $manifest_path = __DIR__ . "/../../$bundle_folder/" . $manifest_name;

        $theme_path = str_replace('cms.', 'www.', get_template_directory_uri());

        if (file_exists($manifest_path)) {
            // die($manifest_path);
            $manifest = json_decode(file_get_contents($manifest_path), true);

            if (!isset($manifest[$file])) {
                throw new \InvalidArgumentException("File {$file} not defined in asset manifest.");
            }

            return "$theme_path/$bundle_folder/$manifest[$file]";
        }

        return "$theme_path/$bundle_folder/$file";
    }

    /**
     * Unregister taxonomies
     */
    public function lb_unregister_taxonomies()
    {
        register_taxonomy('category', []);
        register_taxonomy('post_tag', []);
    }

    /**
     * Filter main query
     */
    public function lb_post_filters($query)
    {
        // "Brand" and "Linea di Prodotto" Archive pages
        if (is_tax('lb-brand') && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page()) {
            $query->set('post_type', array('product'));
            $query->set('posts_per_page', 12);
        }

        // Product Category Archive page
        // if (is_tax('product_cat') && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page()) {
        //     $query->set('posts_per_page', 12);
        // }

        // Posts page
        if (is_home() && !is_admin() && $query->is_main_query() && !is_front_page() && !is_archive()) {
            $query->set('posts_per_page', 12);
        }

        // Jobs Archive page
        if (is_post_type_archive('lb-job') && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page()) {
            $query->set('posts_per_page', 8);
        }

        // FAQs Archive page
        if (is_post_type_archive('lb-faq') && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page()) {
            $query->set('posts_per_page', 12);
            $query->set('orderby', 'menu_order');
        }

        // Archive page
        // if (is_archive() && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page()) {
        //     $query->set('posts_per_page', 12);
        //     $query->set('ignore_sticky_posts', 1);
        // }

        // Tag page
        // if (is_tag() && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page()) {
        //     $query->set('posts_per_page', 12);
        //     $query->set('ignore_sticky_posts', 1);
        // }

        // Search page
        // if (is_search() && !is_admin() && $query->is_main_query() && !is_home() && !is_front_page() && !is_archive()) {
        //     $query->set('posts_per_page', 12);
        //     $query->set('ignore_sticky_posts', 1);
        // }
    }

    /**
     * ACF register Services API Key
     */
    public function lb_set_services_api_key()
    {
        // Google
        acf_update_setting('google_api_key', (new Option())->getApiKey('lb_api_key_google_maps'));
    }

    /**
     * ACF Config Capability
     */
    public function lb_acf_capability($cap)
    {
        return 'administrator';
    }

    /**
     * ACF Config JSON save point
     */
    public function lb_acf_json_save_point($path)
    {
        // unset($path[0]);
        $path = get_stylesheet_directory() . '/acf-config/fields';
        return $path;
    }

    /**
     * ACF Config JSON load point
     */
    public function lb_acf_json_load_point($path)
    {
        // unset($path[0]);
        $path = get_stylesheet_directory() . '/acf-config/fields';
        return $path;
    }

    /**
     * Logout redirect
     */
    public function lb_logout_redirect()
    {
        wp_redirect(home_url(), 301);
        exit;
    }

    /**
     * Add custom category for components to Gutenberg editor
     */
    public function lb_gutenberg_block_categories($categories)
    {
        return array_merge(
            $categories,
            [
                [
                    'slug'  => 'caffeina-theme',
                    'title' => 'Caffeina Theme Components',
                ],
            ]
        );
    }

    /**
     * Add symbols.twig to WP admin area
     */
    public function lb_add_symbols_to_admin()
    {
        Timber::render('@PathViews/components/symbols.twig');
    }

    /**
     * Manage specific thumbnail sizes
     */
    public function lb_manage_thumbnails()
    {
        foreach (get_intermediate_image_sizes() as $size) {
            if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large', '1536x1536', '2048x2048'))) {
                remove_image_size($size);
            }
        }

        // Remove sizes for medium_large thumb
        update_option('medium_large_size_w', '0');
        update_option('medium_large_size_h', '0');
    }

    /**
     * Disable PDF preview images
     */
    public function lb_disable_pdf_thumbnails()
    {
        $fallbacksizes = array();
        return $fallbacksizes;
    }

    /**
     * Change Yoat SEO Bradcrumbs separator
     */
    public function lb_yoast_breadcrumb_separator($sep)
    {
        return '<span class="lb-icon lb-icon-arrow-right"><svg aria-label="arrow-right" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#arrow-right"></use></svg></span>';
    }

    /**
     * Filter the excerpt length to 20 words
     *
     * @param int $length Excerpt length
     *
     * @return int (Maybe) modified excerpt length
     */
    public function lb_excerpt_length($length)
    {
        if (is_admin()) {
            return $length;
        }
        return 20;
    }

    /**
     * Parse CF7 shortcode tags to implement custom HTML data attributes on fields
     */
    public function lb_parse_cf7_fields($tag)
    {
        $datas = [];
        foreach ((array)$tag['options'] as $option) {
            if (strpos($option, 'data-') === 0) {
                $option = explode(':', $option, 2);
                $data_attribute = $option[0];
                $data_value = str_replace('|', ' ', $option[1]);
                $datas[$data_attribute] = apply_filters('wpcf7_option_value', $data_value, $data_attribute);
            }
        }
        if (!empty($datas)) {
            $id = $name = ($tag['basetype'] == 'select') ? "{$tag['name']}[]" : $tag['name'];
            add_filter('wpcf7_form_elements', function ($content) use ($name, $id, $datas) {
                $custom_name = str_replace('[]', '', $name);
                $custom_id = str_replace('[]', '', $id);
                $html = str_replace($custom_id, $custom_name, str_replace("name=\"$custom_id\"", 'name="'. $custom_name .'" ' . wpcf7_format_atts($datas), $content));
                return $html;
            });
        }
        return $tag;
    }

    /**
     * Replace preview url from 'www.' to 'cms.'
     */
    public function lb_update_preview_post_link($link) {
        $link = preg_replace('/www/', 'cms', $link);
        return $link;
    }

    /**
     * Exclude Single Posts from the Yoast Sitemap
     */
    public function lb_exclude_specific_posts_from_sitemap()
    {
        $posts = get_posts(array(
            'post_type' => array(
                'lb-store',
                'lb-beauty-specialist',
            ),
            'numberposts' => -1,
        ));

        $posts_ids = wp_list_pluck($posts, 'ID');

        return $posts_ids;
    }


    /**
     * Exclude a post type from XML sitemaps
     *
     * @param boolean $excluded  Whether the post type is excluded by default.
     * @param string  $post_type The post type to exclude.
     *
     * @return bool Whether or not a given post type should be excluded.
     */
    public function lb_exclude_post_types_from_sitemap($excluded, $post_type)
    {
        return $post_type === 'lb-distributor';
    }


    /**
     * Add custom roles
     */
    public function lb_update_custom_roles()
    {
        if (get_option('lb_custom_roles_version') < 1) {
            add_role('lb_labo_media', 'Labo Media', [
                'read' => true,
                'level_0' => true,

                'manage_woocommerce' => true,
                'view_admin_dashboard' => true,
                'view_woocommerce_reports' => true,

                'assign_product_terms' => true,
                'edit_others_products' => true,
                'edit_private_products' => true,
                'edit_product' => true,
                'edit_product_terms' => true,
                'edit_products' => true,
                'edit_published_products' => true,
                'read_product' => true,
                'read_private_product' => true,
            ]);

            update_option('lb_custom_roles_version', 1);
        }
    }

    public function lb_update_editor_role()
    {
        $editor = get_role('editor');
        $editor->add_cap('manage_options');
        $editor->add_cap('assign_product_terms');
        $editor->add_cap('assign_shop_coupon_terms');
        $editor->add_cap('assign_shop_order_terms');
        $editor->add_cap('delete_others_products');
        $editor->add_cap('delete_others_shop_coupons');
        $editor->add_cap('delete_others_shop_orders');
        $editor->add_cap('delete_private_products');
        $editor->add_cap('delete_private_shop_coupons');
        $editor->add_cap('delete_private_shop_orders');
        $editor->add_cap('delete_product');
        $editor->add_cap('delete_product_terms');
        $editor->add_cap('delete_products');
        $editor->add_cap('delete_published_products');
        $editor->add_cap('delete_published_shop_coupons');
        $editor->add_cap('delete_published_shop_orders');
        $editor->add_cap('delete_shop_coupon');
        $editor->add_cap('delete_shop_coupon_terms');
        $editor->add_cap('delete_shop_coupons');
        $editor->add_cap('delete_shop_order');
        $editor->add_cap('delete_shop_order_terms');
        $editor->add_cap('delete_shop_orders');
        $editor->add_cap('edit_others_products');
        $editor->add_cap('edit_others_shop_coupons');
        $editor->add_cap('edit_others_shop_orders');
        $editor->add_cap('edit_private_products');
        $editor->add_cap('edit_private_shop_coupons');
        $editor->add_cap('edit_private_shop_orders');
        $editor->add_cap('edit_product');
        $editor->add_cap('edit_product_terms');
        $editor->add_cap('edit_products');
        $editor->add_cap('edit_published_products');
        $editor->add_cap('edit_published_shop_coupons');
        $editor->add_cap('edit_published_shop_orders');
        $editor->add_cap('edit_shop_coupon');
        $editor->add_cap('edit_shop_coupon_terms');
        $editor->add_cap('edit_shop_coupons');
        $editor->add_cap('edit_shop_order');
        $editor->add_cap('edit_shop_order_terms');
        $editor->add_cap('edit_shop_orders');
        $editor->add_cap('manage_product_terms');
        $editor->add_cap('manage_shop_coupon_terms');
        $editor->add_cap('manage_shop_order_terms');
        $editor->add_cap('manage_woocommerce');
        $editor->add_cap('publish_products');
        $editor->add_cap('publish_shop_coupons');
        $editor->add_cap('publish_shop_orders');
        $editor->add_cap('read_private_products');
        $editor->add_cap('read_private_shop_coupons');
        $editor->add_cap('read_private_shop_orders');
        $editor->add_cap('read_product');
        $editor->add_cap('read_shop_coupon');
        $editor->add_cap('read_shop_order');
        $editor->add_cap('view_woocommerce_reports');
    }
}

new ThemeSetup();
new CustomPostType;
new CustomTaxonomy;
new Clean;
new Assets;
new CookiebotDeclarationShortcode;
new WCSignInUpShortcode;
// new SettingsMC4WP;
Message::register();

// function filter_wp_image_editors( $array ) {
//     return array('WP_Image_Editor_Imagick');
// }
// add_filter('wp_image_editors', 'filter_wp_image_editors', 10, 1);

// function filter_jpeg_quality( $int, $edit_image ) {
//     return 90;
// }
// add_filter('jpeg_quality', 'filter_jpeg_quality', 10, 2);
