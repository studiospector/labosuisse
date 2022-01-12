<?php

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

    // add_filter(
    // 	'template_include',
    // 	function( $template ) {
    // 		return get_stylesheet_directory() . '/static/no-timber.html';
    // 	}
    // );

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
        parent::__construct();
    }

    /**
     * Add supports
     */
    public function theme_supports()
    {
        // load_theme_textdomain( 'labo-suisse-theme', LB_DIR_PATH . '/languages' );

        add_theme_support('title-tag');

        add_theme_support('post-thumbnails');

        add_theme_support('post-formats', array('aside', 'gallery'));

        add_image_size('lb-large', 1260, 600, true);
        add_image_size('lb-medium', 650, 470, true);
        add_image_size('lb-small', 400, 345, true);

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

        $theme_path = get_template_directory_uri();

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
}

new ThemeSetup();



/**
 * ACF Config
 */
// ACF Config JSON save point
add_filter('acf/settings/save_json', 'labo_acf_json_save_point');
function labo_acf_json_save_point($path)
{
    // var_dump($path);die; 
    // unset($path[0]);
    $path = get_stylesheet_directory() . '/acf-config/fields';
    return $path;
}

// ACF Config JSON load point
add_filter('acf/settings/load_json', 'labo_acf_json_load_point');
function labo_acf_json_load_point($path)
{
    // unset($path[0]);
    $path = get_stylesheet_directory() . '/acf-config/fields';
    return $path;
}



/**
 * Add custom category for components to Gutenberg editor
 */
add_action('block_categories_all', 'labo_gutenberg_block_categories', 10, 2);
function labo_gutenberg_block_categories($categories)
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
 * Assign global $product object in Timber
 */
function timber_set_product($post)
{
    global $product;
    $product = isset($post->product) ? $post->product : wc_get_product($post->ID);
}



/**
 * Get header and menu
 */
function lb_header() {

    $menu_desktop = array(
        [
            'type' => 'link',
            'label' => 'Link Normal'
        ],
        [
            'type' => 'submenu',
            'label' => 'Submenu',
            'children' => [
                [
                    'type' => 'submenu',
                    'label' => 'Per Zona',
                    'children' => [
                        ['type' => 'link', 'label' => 'Tutte le zone volto', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Volto', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Occhi e sguardo', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Labbra', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Collo e decolleté', 'href' => '#'],
                    ]
                ],
                [
                    'type' => 'submenu',
                    'label' => 'Per Esigenza',
                    'children' => [
                        ['type' => 'link', 'label' => 'Tutte le esigenze', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Anti-età', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Effetto riempitivo', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Lifting', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Pelle sensibile', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Illuminare e uniformare', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Nutrire', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Ri-ossigenare', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Detergere', 'href' => '#'],
                        ['type' => 'link', 'label' => 'Trattamento personalizzato', 'href' => '#'],
                    ]
                ],
            ],
            'fixed' => [
                [
                    'type' => 'card',
                    'data' => [
                        'images' => [
                            'original' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'large' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'medium' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'small' => get_template_directory_uri() . '/assets/images/card-img-5.jpg'
                        ],
                        'infobox' => [
                            'subtitle' => 'Magnetic Eyes',
                            'paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'cta' => [
                                'url' => '#',
                                'title' => 'Scopri di più',
                                'iconEnd' => [ 'name' => 'arrow-right' ],
                                'variants' => ['quaternary']
                            ]
                        ],
                        'variants' => ['type-3']
                    ],
                ],
            ]
        ],
        [
            'type' => 'link',
            'label' => 'Personalize',
            'href' => '#',
        ],
        [
            'type' => 'link',
            'label' => 'Lookbook',
            'href' => '#',
        ],
        [
            'type' => 'submenu',
            'label' => 'World',
            'children' => [
                [
                    'type' => 'submenu',
                    'label' => 'Excellences',
                    'children' => [
                        ['type' => 'link', 'label' => 'link 1', 'href' => '#'],
                        ['type' => 'link', 'label' => 'link 2', 'href' => '#'],
                        ['type' => 'link', 'label' => 'link 3', 'href' => '#'],
                        ['type' => 'link', 'label' => 'link 4', 'href' => '#'],
                    ]
                ],
                [
                    'type' => 'submenu',
                    'label' => 'Lorem Ipsum',
                    'children' => [
                        ['type' => 'link', 'label' => 'link 1', 'href' => '#'],
                        ['type' => 'link', 'label' => 'link 2', 'href' => '#'],
                        ['type' => 'link', 'label' => 'link 3', 'href' => '#'],
                        ['type' => 'link', 'label' => 'link 4', 'href' => '#'],
                    ]
                ],
            ],
        ],
        [
            'type' => 'separator',
        ],
        [
            'type' => 'link',
            'label' => 'Tutti i Brand',
            'href' => '#',
        ],
        [
            'type' => 'link',
            'label' => 'Scopri Labo',
            'href' => '#',
        ],
        [
            'type' => 'separator',
        ],
        [
            'type' => 'icon',
            'icon' => [
                'name' => 'heart'
            ],
            'href' => 'https://google.it'
        ],
        [
            'type' => 'icon',
            'icon' => [
                'name' => 'cart'
            ],
            'href' => wc_get_cart_url()
        ],
        [
            'type' => 'icon',
            'icon' => [
                'name' => 'user'
            ],
            'href' => 'https://google.it'
        ],
    );

    $menu_mobile = [
        'children' => [
            [
                'type' => 'link',
                'label' => 'Link Normal'
            ],
            [
                'type' => 'submenu', 
                'label' => 'Lorem', 
                'children' => [
                    [
                        'type' => 'submenu', 
                        'label' => 'Ipsum 1', 
                        'children' => [
                            ['type' => 'link', 'label' => 'link 1'],
                            ['type' => 'link', 'label' => 'link 2'],
                            ['type' => 'link', 'label' => 'link 3'],
                        ]
                    ],
                    [
                        'type' => 'submenu', 
                        'label' => 'Ipsum 2', 
                        'children' => [
                            ['type' => 'link', 'label' => 'link 1'],
                            ['type' => 'link', 'label' => 'link 2'],
                            ['type' => 'link', 'label' => 'link 3'],
                        ]
                    ]
                ]
            ],
            [
                'type' => 'submenu', 
                'label' => 'Submenu',
                'children' => [
                    [
                        'type' => 'submenu',
                        'label' => 'Per Zona',
                        'children' => [
                            ['type' => 'link', 'label' => 'Tutte le zone volto', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Volto', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Occhi e sguardo', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Labbra', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Collo e decolleté', 'href' => '#'],
                        ]
                    ],
                    [
                        'type' => 'submenu',
                        'label' => 'Per Esigenza',
                        'children' => [
                            ['type' => 'link', 'label' => 'Tutte le esigenze', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Anti-età', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Effetto riempitivo', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Lifting', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Pelle sensibile', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Illuminare e uniformare', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Nutrire', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Ri-ossigenare', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Detergere', 'href' => '#'],
                            ['type' => 'link', 'label' => 'Trattamento personalizzato', 'href' => '#'],
                        ]
                    ],
                ],
            ]
        ],
        'fixed' => [
            [
                'type' => 'card',
                'data' => [
                    'images' => [
                        'original' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        'large' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        'medium' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        'small' => get_template_directory_uri() . '/assets/images/card-img-5.jpg'
                    ],
                    'infobox' => [
                        'subtitle' => 'Magnetic Eyes',
                        'paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                        'cta' => [
                            'url' => '#',
                            'title' => 'Scopri di più',
                            'iconEnd' => ['name' => 'arrow-right'],
                            'variants' => ['quaternary']
                        ]
                    ],
                    'variants' => ['type-1']
                ],
            ],
            [
                'type' => 'small-link', 
                'label' => 'Hai bisogno di aiuto?', 
                'icon' => 'comments',
            ],
            [
                'type' => 'small-link',
                'label' => 'Italia',
                'icon' => 'earth',
            ],
        ]
    ];

    return array(
        'menu_desktop' => ['items' => $menu_desktop],
        'menu_mobile' => ['items' => $menu_mobile],
    );
}



/**
 * Get footer and prefooter
 */
function lb_footer() {

    $prefooter = [
        'block_1' => [
            'title' => 'Hai delle domande?',
            'text' => 'Scegli il tuo canale di comunicazione preferito dalla pagina di assistenza e mettiti in contatto con un esperto.',
            'cta' => [
                'url' => '#',
                'title' => 'Vai all’assistenza',
                'iconEnd' => [ 'name' => 'arrow-right' ],
                // Variants is static
                'variants' => ['quaternary']
            ],
        ],
        'block_2' => [
            'title' => 'Trova un punto vendita',
            'text' => 'Inserisci un CAP, una città o un indirizzo per scoprire i rivenditori vicinot a te.',
            'input' => [
                // The only parameter to manage in options is label
                'type' => 'search',
                'name' => "lb-search-store-prefooter",
                'label' => "Inserisci città, provincia, CAP,...",
                'disabled' => false,
                'required' => false,
                'variants' => ['secondary'],
            ],
        ],
        'block_3' => [
            'title' => 'Iscriviti alla newsletter',
            'text' => 'Inserisci i tuoi dati e ricevi direttamente sulla tua mail aggiornamenti e promozioni dal mondo Labo.',
            'cta' => [
                'url' => '#',
                'title' => 'Procedi e iscriviti',
                'iconEnd' => [ 'name' => 'arrow-right' ],
                // Variants is static
                'variants' => ['quaternary']
            ],
        ],
    ];

    $footer = [
        'discover' => [
            'title' => 'SCOPRI LABO',
            'items' => [
                [
                    'url' => '#',
                    'title' => 'Labo Magazine',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Labo nel Mondo',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Lavora con noi',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Diventa farmacia concessonaria',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Consulenza personalizzata',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Privacy Policy',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
            ],
        ],
        'support' => [
            'title' => 'ASSISTENZA',
            'items' => [
                [
                    'url' => '#',
                    'title' => 'FAQ',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Contatti',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
                [
                    'url' => '#',
                    'title' => 'Traccia un ordine',
                    'target' => '_self',
                    // Variants is static
                    'variants' => ['link', 'thin', 'small']
                ],
            ], 
        ],
        'search' => [
            'title' => 'TROVA UNA FARMACIA CONCESSIONARIA ',
            'input' => [
                // The only parameter to manage in options is label
                'type' => 'search',
                'name' => "lb-search-store-footer",
                'label' => "Inserisci città, provincia, CAP,...",
                'disabled' => false,
                'required' => false,
                'variants' => ['secondary'],
            ],
        ],
        'newsletter' => [ 
            'title' => 'ISCRIVITI ALLA NEWSLETTER',
            'text' => 'Ricevi aggiornamenti e promozioni<br>direttamente sulla tua mail.',
            'cta' => [
                'url' => '#',
                'title' => 'Procedi e iscriviti',
                // Variants is static
                'variants' => ['secondary']
            ],
        ],
        'social' => [
            'title' => 'SEGUICI',
            'items' => [
                [
                    'url' => '#',
                    'icon' => 'instagram',
                ],
                [
                    'url' => '#',
                    'icon' => 'facebook',
                ],
                [
                    'url' => '#',
                    'icon' => 'twitter',
                ],
                [
                    'url' => '#',
                    'icon' => 'youtube',
                ],
                [
                    'url' => '#',
                    'icon' => 'linkedin',
                ],
                [
                    'url' => '#',
                    'icon' => 'whatsapp',
                ],
            ]
        ],
    ];

    return array(
        'footer' => $footer,
        'prefooter' => $prefooter,
    );
}
