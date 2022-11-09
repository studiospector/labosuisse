<?php

namespace Caffeina\LaboSuisse\Setup;

class Assets
{
    public function __construct()
    {
        // Enqueue site scripts
        add_action('wp_enqueue_scripts', [$this, 'lb_register_scripts'], 999);
        // Enqueue site style
        add_action('wp_enqueue_scripts', [$this, 'lb_register_styles'], 999);
        add_action('wp_head', function() {
            wp_dequeue_style('cbr-fronend-css');
        }, 999);
        // Enqueue admin style
        add_action('admin_enqueue_scripts', [$this, 'lb_register_admin_styles']);
        // Enqueue admin login page style
        add_action('login_enqueue_scripts', [$this, 'lb_enqueue_admin_login_scripts']);
        // Enqueue editor scripts and styles
        add_action('enqueue_block_assets', [$this, 'lb_enqueue_editor_assets']);

        if (is_front_page()) {
            add_filter('wpcf7_load_js', '__return_false');
            add_filter('wpcf7_load_css', '__return_false');
        }
    }


    /**
     * Enqueue site scripts
     */
    public function lb_register_scripts()
    {
        // Register scripts
        // wp_register_script('lb-main', LB_BUILD_JS_URI . '/main.js', [], filemtime(LB_BUILD_JS_DIR_PATH . '/main.js'), true);

        // Enqueue scripts
        // wp_enqueue_script('lb-main');

        // Dequeue jQuery
        wp_deregister_script('jquery');
        wp_dequeue_script('jquery');

        // Enqueue jQuery
        wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.1.min.js', [], '', false);
        wp_enqueue_script('jquery');

        // Dequeue other scripts
        // if (!is_admin() && (!is_cart() || !is_checkout() || !is_account_page())) {}
        
        if (is_front_page()) {
            wp_dequeue_script('selectWoo');
            wp_deregister_script('selectWoo');
            wp_dequeue_script('wcml-mc-scripts');

            wp_dequeue_script('contact-form-7');
            wp_dequeue_script('contact-form-7-js-extra');

            wp_dequeue_script('google-recaptcha');
            wp_deregister_script('google-recaptcha');

            wp_dequeue_script('wc-add-payment-method');
            wp_dequeue_script('wc-lost-password');
            wp_dequeue_script('wc_price_slider');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-add-to-cart');
            // wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('wc-credit-card-form');
            wp_dequeue_script('wc-checkout');
            wp_dequeue_script('wc-add-to-cart-variation');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('prettyPhoto');
            wp_dequeue_script('prettyPhoto-init');
            // wp_dequeue_script('jquery-blockui');
            // wp_dequeue_script('jquery-placeholder');
            // wp_dequeue_script('jquery-payment');
            wp_dequeue_script('fancybox');
            wp_dequeue_script('jqueryui');

            wp_dequeue_script('ppcp-smart-button');
            wp_dequeue_script('wcml-front-scripts');
            wp_dequeue_script('cart-widget');

            wp_dequeue_script('cbr-pro-front-js');
            wp_dequeue_script('select2-cbr');

            wp_dequeue_script('awdr-main');
            wp_dequeue_script('awdr-dynamic-price');

            wp_dequeue_script('woo_discount_pro_script');
        }
    }


    /**
     * Enqueue site style
     */
    public function lb_register_styles()
    {
        // Register styles
        // wp_register_style('lb-main', LB_BUILD_CSS_URI . '/main.css', [], filemtime(LB_BUILD_CSS_DIR_PATH . '/main.css'), 'all');

        // Enqueue style
        // wp_enqueue_style('lb-main');

        // Dequeue styles
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        // wp_dequeue_style('wp-block-library-css');
        wp_dequeue_style('classic-theme-styles');

        wp_dequeue_style('wc-blocks-style');

        wp_dequeue_style('wcml-dropdown-0');
        wp_dequeue_style('wpml-legacy-horizontal-list-0');

        if (!is_admin() && !is_user_logged_in()) {
            wp_deregister_style('dashicons');
            wp_dequeue_style('global-styles');
        }

        // if (!is_admin() && (!is_cart() || !is_checkout() || !is_account_page())) {}
        
        if (is_front_page()) {
            wp_dequeue_style('select2');
            wp_deregister_style('select2');

            wp_dequeue_style('woocommerce_frontend_styles');
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_style('woocommerce_fancybox_styles');
            wp_dequeue_style('woocommerce_chosen_styles');
            wp_dequeue_style('woocommerce_prettyPhoto_css');
            wp_dequeue_style('woocommerce-inline');

            wp_dequeue_style('contact-form-7');

            wp_dequeue_style('cbr-pro-front-css');
            wp_dequeue_style('select2-cbr');

            wp_dequeue_style('woo_discount_pro_style');
        }
    }


    /**
     * Enqueue admin style
     */
    public function lb_register_admin_styles()
    {
        // Register styles
        wp_register_style('lb-custom-admin', LB_BUILD_CSS_URI . '/admin.css', [], filemtime(LB_BUILD_CSS_DIR_PATH . '/admin.css'), 'all');

        // Enqueue Styles
        wp_enqueue_style('lb-custom-admin');
    }


    /**
     * Enqueue admin login page style
     */
    public function lb_enqueue_admin_login_scripts()
    {
        wp_enqueue_style('lb-login-custom-style', LB_BUILD_CSS_URI . '/admin.css', 'all');
    }


    /**
     * Enqueue editor scripts and styles
     * 
     * Note:
     * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend
     * except when is_admin() is used to include them conditionally
     */
    public function lb_enqueue_editor_assets()
    {
        if (is_admin()) {
            // Gutenberg blocks JS
            wp_enqueue_script(
                'lb-blocks-js',
                LB_BUILD_JS_URI . '/blocks.js',
                ['wp-polyfill'],
                filemtime(LB_BUILD_JS_DIR_PATH . '/blocks.js'),
                true
            );

            // Gutenberg blocks CSS
            wp_enqueue_style(
                'lb-blocks-css',
                LB_BUILD_CSS_URI . '/blocks.css',
                ['wp-block-library-theme', 'wp-block-library',],
                filemtime(LB_BUILD_CSS_DIR_PATH . '/blocks.css'),
                'all'
            );
        }
    }
}
