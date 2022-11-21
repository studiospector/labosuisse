<?php

/**
 * CCBR_Restrictions
 *
 * @class   CCBR_Restrictions
 * @package WooCommerce/Classes
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * CCBR_Restrictions class
 *
 * @since  1.0
 */
class CCBR_Restrictions
{
    /**
     * Instance of this class.
     *
     * @since  1.0
     * @var object Class Instance
     */
    private static $instance;

	private $country;

    private $availableCountries = [
        'BE',
        'FR',
        'DE',
        'IE',
        'NL',
        'ES'
    ];

    /**
     * Get the class instance
     *
     * @return CCBR_Restrictions
     * @since  1.0
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Initialize the main plugin function
     *
     * @since  1.0
     */
    public function __construct()
    {
		$this->init();
		$this->country = null;
    }

    /**
     * Init function
     *
     * @since  1.0
     */
    public function init()
    {
        // Callback on activate plugin
        register_activation_hook(__FILE__, array($this, 'on_activation'));

        // Hook for geolocation_update_database
        add_filter('woocommerce_maxmind_geolocation_update_database_periodically', array($this, 'update_geo_database'), 10, 1);

        if (!is_admin()) {
            add_action('init', function() {
                $this->country = $this->geolocate();
            });

            // Check if product is purchasable
            add_filter('woocommerce_is_purchasable', array($this, 'is_purchasable'), 1, 2);
            // add_filter('woocommerce_variation_is_purchasable', array($this, 'is_purchasable'), 1, 2);
        }
    }

    /**
     * WC_Geolocation database update hooks
     *
     * @since 1.0
     *
     */
    function on_activation()
    {
        WC_Geolocation::update_database();
    }

    /**
     * Update geo database
     *
     * @since 1.0
     */
    function update_geo_database()
    {
        return true;
    }

    /**
     * Check product is_purchasable or not
     *
     * @since  1.0
     */
    public function is_purchasable($is_purchasable, $product)
    {
        if (!is_admin()) {
            global $sitepress;

            // Current site lang
            $current_lang = $sitepress->get_current_language();
            // Product lang
            $wpml_product_details = apply_filters('wpml_post_language_details', null, $product->get_id());

            // Check for IT Catalog => Countries -> IT
            if ($current_lang == 'it' && ($current_lang == $wpml_product_details['language_code']) && $this->country != 'IT') {
                return false;
                // Check for EN Catalog => Countries -> BE, IT, FR, IE, ES, NL, DE
            } elseif ($current_lang == 'en' && ($current_lang == $wpml_product_details['language_code']) && !in_array($this->country, $this->availableCountries)) {
                return false;
            }
        }

        return $is_purchasable;
    }

    private function geolocate()
    {
		if (isset($_COOKIE['country'])) {
			return $_COOKIE['country'];
		}

        if (wp_get_environment_type() == 'local') {
            $ip = \WC_Geolocation::get_external_ip_address();
        } else {
            $ip = \WC_Geolocation::get_ip_address();
        }

        return $this->getLocationInfo($ip);
    }

    private function getLocationInfo($ip)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => "http://ip-api.com/json/{$ip}",
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

		setcookie('country', $response->countryCode, time()+31556926);

        return $response->countryCode;
    }
}
