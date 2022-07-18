<?php

namespace Caffeina\LaboSuisse\Shortcodes;

class CookiebotDeclarationShortcode
{
    public function __construct()
    {
        add_shortcode('lb_cookie_declaration', array(static::class, 'show_declaration'));
    }

    /**
     * Output declation shortcode [lb_cookie_declaration]
     * Support attribute lang="LANGUAGE_CODE". Eg. lang="en".
     */
    public static function show_declaration($shortcode_attributes)
    {
        $cookiebot_domain_id = get_field('lb_cookiebot_key', 'option');

        if (!empty($cookiebot_domain_id)) {
            $url = 'https://consent.cookiebot.com/' . $cookiebot_domain_id . '/cd.js';
            $shortcode_attributes = shortcode_atts(
                array(
                    'lang' => lb_get_current_lang(),
                ),
                $shortcode_attributes,
                'lb_cookie_declaration'
            );

            $lang = empty($shortcode_attributes['lang']) ? '' : strtoupper($shortcode_attributes['lang']);

            ob_start();

            ?>
            <div class="lb-cookiebot-declaration container">
                <script type="text/javascript"
                        id="CookieDeclaration"
                        src="<?php echo esc_url($url); ?>"
                        <?php if (!empty($lang)) : ?>
                            data-culture="<?php echo esc_attr($lang); ?>"
                        <?php endif; ?>
                        async
                ></script>
            </div>
            <?php

            return (string) ob_get_clean();
        } else {
            return esc_html__('Aggiungi il tuo ID Cookiebot per mostrare le dichiarazioni sui cookie.', 'labo-suisse-theme');
        }
    }
}
