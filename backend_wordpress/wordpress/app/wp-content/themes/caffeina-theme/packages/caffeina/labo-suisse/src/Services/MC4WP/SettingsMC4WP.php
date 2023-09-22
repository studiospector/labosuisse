<?php

namespace Caffeina\LaboSuisse\Services\MC4WP;

class SettingsMC4WP
{
    // private $IDlistIT = null;
    // private $IDlistEN = null;

    public function __construct()
    {
        // $this->IDlistIT = wp_get_environment_type() == 'production' ? 'a154247d71' : '4c7a6d16ad';
        // $this->IDlistEN = wp_get_environment_type() == 'production' ? 'b2ae793ca5' : 'e11ac615ab';

        add_filter('mc4wp_subscriber_data', [$this, 'lb_set_mc4wp_subscriber_lang']);
        // add_filter('mc4wp_lists', [$this, 'lb_filter_mc4wp_lists']);
        // add_filter('mc4wp_integration_woocommerce_subscriber_data', [$this, 'lb_set_mc4wp_tag_on_checkout']);
        // add_action('mc4wp_integration_before_checkbox_wrapper', [$this, 'lb_add_custom_html_before_checkbox_mc4wp']);
        // add_action('mc4wp_integration_after_checkbox_wrapper', [$this, 'lb_add_custom_html_after_checkbox_mc4wp']);
    }

    /**
     * Get site lang
     */
    public function lb_wpml_get_current_language_code() {
        return (string) apply_filters( 'wpml_current_language', null );
    }

    /**
     * Set site lang on all subscribers
     */
    public function lb_set_mc4wp_subscriber_lang(\MC4WP_MailChimp_Subscriber $subscriber)
    {
        $lang_code = $this->lb_wpml_get_current_language_code();

        // do nothing if WPML is not activated
        if (!$lang_code) {
            return $subscriber;
        }

        $subscriber->language = substr($lang_code, 0, 2);

        return $subscriber;
    }

    /**
     * Tell MC4WP to subscribe to a certain list based on the WPML language that is being viewed
     */
    public function lb_filter_mc4wp_lists($lists)
    {
        // do nothing if WPML is not activated
        if (!defined('ICL_LANGUAGE_CODE')) {
            return $lists;
        }

        if (is_checkout()) {
            switch (ICL_LANGUAGE_CODE) {
                case 'it':
                    $lists = array($this->IDlistIT);
                    break;
                case 'en':
                    $lists = array($this->IDlistEN);
                    break;
            }
        }
        return $lists;
    }

    /**
     * Set tag to all new subscribers added using WooCommerce Checkout integration
     */
    public function lb_set_mc4wp_tag_on_checkout(\MC4WP_MailChimp_Subscriber $subscriber)
    {
        // do nothing if WPML is not activated
        if (!defined('ICL_LANGUAGE_CODE')) {
            return $subscriber;
        }

        switch (ICL_LANGUAGE_CODE) {
            case 'it':
                $subscriber->tags[] = 'Ordine in corso';
                break;
            case 'en':
                $subscriber->tags[] = 'Order in progress';
                break;
        }

        return $subscriber;
    }

    /**
     * Add custom HTML before checkbox integration
     */
    public function lb_add_custom_html_before_checkbox_mc4wp()
    {
        ob_start();
        ?>
        <div class="custom-field custom-checkbox custom-checkbox--vertical" style="padding-bottom: 0;">
            <div class="custom-checkbox__options">
        <?php
        $html = ob_get_clean();
        echo $html;
    }

    /**
     * Add custom HTML after checkbox integration
     */
    public function lb_add_custom_html_after_checkbox_mc4wp()
    {
        ob_start();
        ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        echo $html;
    }
}
