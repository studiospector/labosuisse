<?php

namespace Caffeina\LaboSuisse\Shortcodes;

class WCSignInUpShortcode
{
    public function __construct()
    {
        add_shortcode('lb_wc_sign_in_up', array(static::class, 'forms'));
    }

    /**
     * Output declaration shortcode [lb_wc_sign_in_up]
     * 
     * No Supported attributes.
     */
    public static function forms($shortcode_attributes)
    {
        // $shortcode_attributes = shortcode_atts(
        //     array(
        //         'lorem' => 'ipsum',
        //     ),
        //     $shortcode_attributes,
        //     'lb_wc_sign_in_up'
        // );

        if (!is_user_logged_in()) {
            ob_start();
            wc_get_template('myaccount/form-login.php');
            return (string) ob_get_clean();
        } else {
            wp_safe_redirect(wc_get_account_endpoint_url('dashboard'), 301);
            exit;
        }
    }
}
