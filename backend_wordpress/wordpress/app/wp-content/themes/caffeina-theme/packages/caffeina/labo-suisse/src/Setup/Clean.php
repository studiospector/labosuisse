<?php

namespace Caffeina\LaboSuisse\Setup;

class Clean
{
    public function __construct()
    {
        // Disable REST API
        $this->lb_disable_rest_api_users();

        // Remove trash action
        add_action('init', [$this, 'remove_trash_actions']);

        // Remove default dashboard widgets
        add_action('wp_dashboard_setup', [$this, 'clean_dashboard_widgets'], 999);

        // Disable pdf previews
        add_filter('fallback_intermediate_image_sizes', '__return_empty_array');

        // Remove wordpress content that is generated on the wp_head hook
        add_action('init', [$this, 'remove_head_thrash_action']);

        // Remove emoji support
        add_action('init', [$this, 'remove_emoji_support']);

        // Redirect admin pages
        add_action( 'admin_init', [$this, 'lb_disallowed_admin_pages'] );

        // Manage admin menu
        add_action('admin_menu', [$this, 'lb_manage_admin_menu'], 999);

        // Change labels admin menu
        add_action('admin_menu', [$this, 'lb_change_labels_admin_menu'], 999);

        add_filter('custom_menu_order', [$this, 'lb_custom_menu_order'], 10, 1);
        add_filter('menu_order', [$this, 'lb_custom_menu_order'], 10, 1);

        // Clean admin bar
        add_action('wp_before_admin_bar_render', [$this, 'clean_admin_bar'], 999);

        // Adding favicon to admin
        // add_action('admin_head', function () {
        //     echo '<link rel="icon" href="' . $this->theme_options['abbrivio-site-favicon'] . '" type="image/x-icon">';
        //     echo '<link rel="shortcut icon" href="' . $this->theme_options['abbrivio-site-favicon'] . '" type="image/x-icon">';
        // });

        // Admin redirect
        add_filter('init', function () {
            if (is_admin()) {
                global $pagenow;

                if (($pagenow == 'profile.php') && (!lb_user_has_role('administrator'))) {
                    wp_redirect(get_dashboard_url());
                    exit;
                }
            }
        });

        // Change the logo link from wordpress.org to my site
        add_filter('login_headerurl', function () {
            return home_url();
        });

        // Remove admin footer version
        if (!lb_user_has_role('administrator')) {
            add_filter('update_footer', '__return_empty_string', 11);
        }

        // Disable the message - JQMIGRATE: Migrate is installed, version x.x.x
        add_action('wp_default_scripts', function ($scripts) {
            if (!empty($scripts->registered['jquery'])) {
                $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
            }
        });

        // Change admin footer text
        add_filter('admin_footer_text', function () {
            ob_start();
            ?>
            <div class="credits">
                <a target="_blank" rel="nofollow" href="https://caffeina.com/" title="Caffeina">
                    <> with <span class="heart">❤</span> by Caffeina
                </a>
            </div>
            <?php
            // Output the content
            $output = ob_get_clean();
            echo $output;
        });

        // Add custom logo to admin pages
        add_action('admin_head', function () {
            ob_start();
            ?>
            <style>
                /* Admin Bar */
                #wpadminbar #wp-admin-bar-site-name > .ab-item::before {
                    background-image: url('<?php echo LB_BUILD_IMG_URI; ?>/logo-min.svg') !important;
                }
                /* Footer text */
                /* #wpfooter .credits a {
                    background-image: url('');
                } */
            </style>
            <?php
            // Output the content
            $output = ob_get_clean();
            echo $output;
        });

        // Add custom logo to login admin page
        add_action('login_head', function () {
            ob_start();
            ?>
            <style>
                body.login div#login h1 a {
                    background-image: url('<?php echo LB_BUILD_IMG_URI; ?>/logo.svg');
                }
            </style>
            <?php
            // Output the content
            $output = ob_get_clean();
            echo $output;
        });

        add_action('admin_head', function () {
            ob_start();
            if (lb_user_has_role('shop_manager')) {
                ?>
                <style>
                    .otgs-notice,
                    .otgs-installer-notice,
                    #robotsmessage {
                        display: none !important;
                    }
                </style>
                <?php
            }
            // Output the content
            $output = ob_get_clean();
            echo $output;
        });

        // Welcome panel customization
        add_action('load-index.php', [$this, 'always_show_welcome_panel']);
        remove_action('welcome_panel', 'wp_welcome_panel');
        add_action('welcome_panel', [$this, 'custom_welcome_panel']);
    }


    /**
	 * Disable REST API
	 */
    public static function lb_disable_rest_api_users()
    {
        if (!is_user_logged_in() || !lb_user_has_role(['administrator', 'editor', 'shop_manager', 'lb_labo_media'])) {

            remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('template_redirect', 'rest_output_link_header', 11);

            /**
             * Filter all API
             * @see https://developer.wordpress.org/reference/hooks/rest_authentication_errors/
             */
            // add_filter('rest_authentication_errors', function ($access) {
            //     $error_message = esc_html__('Error: Restricted access to the REST API.');
            //     if (is_wp_error($access)) {
            //         $access->add('rest_cannot_access', $error_message, array('status' => rest_authorization_required_code()));
            //         return $access;
            //     }
            //     return new \WP_Error('rest_cannot_access', $error_message, array('status' => rest_authorization_required_code()));
            // }, 20);

            // Restrict users endpoint
            add_filter('rest_endpoints', function ($endpoints) {
                if (isset($endpoints['/wp/v2/users'])) {
                    unset($endpoints['/wp/v2/users']);
                }
                if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
                    unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
                }
                return $endpoints;
            });
        }
    }


    /**
     * Remove trash action
     */
    public function remove_trash_actions()
    {
        // Disable xmlrpc.php
        add_filter('xmlrpc_enabled', '__return_false');

        if (!lb_user_has_role('administrator')) {
            // Removes the profile.php admin color scheme options
            remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
            // Remove help (Aiuto) tab
            add_action('admin_head', function () {
                $screen = get_current_screen();
                $screen->remove_help_tabs();
                // Remove notice message of core update
                remove_action('admin_notices', 'update_nag', 3);
            });
        }

        if (!lb_user_has_role(array('administrator', 'editor'))) {
            // Remove admin bar
            show_admin_bar(false);
            // Remove screen option (Impostazioni schermata) tab
            add_filter('screen_options_show_screen', '__return_false');
        }
    }


    /**
     * Remove default widget
     */
    public function clean_dashboard_widgets()
    {
        if (!lb_user_has_role('administrator')) {
            // Remove the 'Welcome' panel
            remove_action('welcome_panel', 'wp_welcome_panel');
            // Remove 'Site health' metabox
            remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
            // Remove the 'At a Glance' metabox
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
            // Remove the 'Activity' metabox
            remove_meta_box('dashboard_activity', 'dashboard', 'normal');
            // Remove the 'WordPress News' metabox
            remove_meta_box('dashboard_primary', 'dashboard', 'side');
            // Remove the 'Quick Draft' metabox
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
            // Remove WooCommerce metabox
            remove_meta_box('wc_admin_dashboard_setup', 'dashboard', 'normal');
            // Remove Yoast SEO metabox
            remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
            // Remove WP SMTP metabox
            remove_meta_box('wp_mail_smtp_reports_widget_lite', 'dashboard', 'normal');
            // Remove MC4WP metabox
            remove_meta_box('mc4wp_log_widget', 'dashboard', 'normal');
            
        }
    }


    /**
     * Remove wordpress content that is generated on the wp_head hook
     */
    public function remove_head_thrash_action()
    {
        // Remove the Really Simple Discovery service link
        remove_action('wp_head', 'rsd_link');

        // Remove the link to the Windows Live Writer manifest
        remove_action('wp_head', 'wlwmanifest_link');

        // Remove the general feeds
        remove_action('wp_head', 'feed_links', 2);

        // Remove the extra feeds, such as category feeds
        remove_action('wp_head', 'feed_links_extra', 3);

        // Remove the displayed XHTML generator
        remove_action('wp_head', 'wp_generator');

        // Remove the REST API link tag
        remove_action('wp_head', 'rest_output_link_wp_head', 10);

        // Remove oEmbed discovery links.
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

        // Remove rel next/prev links
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

        // Remove prefetch url
        remove_action('wp_head', 'wp_resource_hints', 2);

        // Remove oEmbed-specific JavaScript from the front-end and back-end
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }


    /**
     * Remove emoji support
     */
    public function remove_emoji_support()
    {
        // Front-end
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Admin
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');

        // Feeds
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');

        // Embeds
        remove_filter('embed_head', 'print_emoji_detection_script');

        // Emails
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        // Disable from TinyMCE editor. Disabled in block editor by default
        add_filter(
            'tiny_mce_plugins',
            function ($plugins) {
                if (is_array($plugins)) {
                    $plugins = array_diff($plugins, array('wpemoji'));
                }

                return $plugins;
            }
        );

        /**
         * Finally, disable it from the database also, to prevent characters from converting
         * There used to be a setting under Writings to do this
         * Not ideal to get & update it here - but it works :/
         */
        if ((int) get_option('use_smilies') === 1) {
            update_option('use_smilies', 0);
        }
    }


    /**
     * Redirect admin pages
     */
    function lb_disallowed_admin_pages() {

        // global $pagenow;

        if (!lb_user_has_role('administrator') && is_admin()) {
            // W3TC pages
            if ( isset($_GET['page']) && preg_match("/w3tc_/i", $_GET['page']) ) {
                wp_redirect( admin_url( '/index.php' ) );
                exit;
            }
        }
    }


    /**
     * Manage Menu admin
     */
    function lb_manage_admin_menu()
    {
        // Comments
        remove_menu_page('edit-comments.php');

        if (!lb_user_has_role('administrator')) {
            // Tools
            remove_menu_page('tools.php');
            // Profile
            remove_menu_page('profile.php');
            // Theme
            remove_submenu_page('themes.php', 'themes.php');
            // Customize
            remove_submenu_page('themes.php', 'customize.php?return=' . urlencode($_SERVER['REQUEST_URI']));
            // Yoast
            remove_menu_page('wpseo_workouts');
            // CF7
            remove_menu_page('wpcf7');
            // WPML
            remove_menu_page('tm/menu/main.php');
            // W3TC
            remove_menu_page('w3tc_dashboard');
        }
        
        if (lb_user_has_role('shop_manager')) {
            // Tools
            remove_menu_page('export-personal-data.php');
            // Settings
            remove_menu_page('options-general.php');
            // WP Mail SMTP
            remove_menu_page('wp-mail-smtp');
            // Mailchimp
            remove_menu_page('mailchimp-for-wp');
            // Under construction
            remove_menu_page('under-construction');
            // WP All Import/Export
            remove_menu_page('pmxe-admin-home');
            remove_menu_page('pmxi-admin-home');
            // Separator
            remove_menu_page('separator2');
        }
    }


    /**
     * Change label Menu admin
     */
    function lb_change_labels_admin_menu()
    {
        global $menu;

        $acf_menu_key = lb_recursive_array_search('edit.php?post_type=acf-field-group', $menu);
        $cf7_menu_key = lb_recursive_array_search('wpcf7', $menu);
        
        if ($acf_menu_key) {
            $menu[$acf_menu_key][0] = 'ACF Pro';
        }

        if ($cf7_menu_key) {
            $menu[$cf7_menu_key][0] = 'CF7';
        }
    }


    /**
     * Order position of admin menu
     */
    function lb_custom_menu_order( $menu_ord ) {
        if ( !$menu_ord ) return true;
    
        if (lb_user_has_role('administrator')) {
            return array(
                'index.php', // Dashboard
                'separator1', // First separator
                'lb-theme-general-settings', // Theme options
                'upload.php', // Media
                'edit.php', // Posts
                'edit.php?post_type=page', // Pages
                'edit.php?post_type=lb-brand-page',// Brand pages
                'edit.php?post_type=lb-faq', // FAQ
                'edit.php?post_type=lb-job', // Job
                'edit.php?post_type=lb-store', // Store
                'edit.php?post_type=lb-beauty-specialist', // Beauty specialist
                'edit.php?post_type=lb-distributor', // Distributor
                'separator-woocommerce', // Last separator
                'edit.php?post_type=product', // Product
                'woocommerce', // WooCommerce
                'wc-admin&path=/analytics/overview', // WooCommerce
                'woocommerce-marketing', // WooCommerce
                'separator2', // Second separator
                'themes.php', // Appearance
                'users.php', // Users
                'tools.php', // Tools
                'options-general.php', // Settings
                'plugins.php', // Plugins
                'separator-last', // Last separator
                'edit.php?post_type=acf-field-group',
            );
        } else {
            return array(
                'index.php', // Dashboard
                'separator1', // First separator
                'lb-theme-general-settings', // Theme options
                'upload.php', // Media
                'edit.php', // Posts
                'edit.php?post_type=page', // Pages
                'edit.php?post_type=lb-brand-page',// Brand pages
                'edit.php?post_type=lb-faq', // FAQ
                'edit.php?post_type=lb-job', // Job
                'edit.php?post_type=lb-store', // Store
                'edit.php?post_type=lb-beauty-specialist', // Beauty specialist
                'edit.php?post_type=lb-distributor', // Distributor
                'separator-woocommerce', // Last separator
                'edit.php?post_type=product', // Product
                'woocommerce', // WooCommerce
                'separator2', // Second separator
                'themes.php', // Appearance
            );
        }

        return $menu_ord;
    }


    /**
     * Clean admin bar
     */
    public function clean_admin_bar()
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_menu('wp-logo');
        $wp_admin_bar->remove_menu('wpseo-menu');
        $wp_admin_bar->remove_menu('comments');

        if (!lb_user_has_role('administrator')) {
            // W3TC
            $wp_admin_bar->remove_node('w3tc_flush');
            $wp_admin_bar->remove_node('w3tc_feature_showcase');
            $wp_admin_bar->remove_node('w3tc_settings_general');
            $wp_admin_bar->remove_node('w3tc_settings_extensions');
            $wp_admin_bar->remove_node('w3tc_settings_faq');
            $wp_admin_bar->remove_node('w3tc_support');
            // $wp_admin_bar->remove_menu('user-info');
            // $wp_admin_bar->remove_menu('edit-profile');
        }
    }


    /**
     * Show always welcome panel
     */
    public function always_show_welcome_panel()
    {
        $user_id = get_current_user_id();

        if (1 != get_user_meta($user_id, 'show_welcome_panel', true))
            update_user_meta($user_id, 'show_welcome_panel', 1);
    }


    /**
     * Custom welcome panel in dashboard
     */
    public function custom_welcome_panel()
    {
        ob_start();
        ?>
        <div class="lb-welcome-panel-content welcome-panel-content">
            <div class="lb-welcome-panel-content__logo">
                <img src="<?php echo LB_BUILD_IMG_URI; ?>/logo.svg" />
            </div>
            <div class="lb-welcome-panel-content__text">
                <h1>Benvenuto nel CMS di Labo Suisse!</h1>
                <p>Da quest'area puoi gestire tutti i contenuti relativi al <a href="https://www.labosuisse.com" target="_blank">sito</a>.</p>
            </div>
        </div>
        <?php
        // Output the content
        $output = ob_get_clean();
        echo $output;
    }
}
