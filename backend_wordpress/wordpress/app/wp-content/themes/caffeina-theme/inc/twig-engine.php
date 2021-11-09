<?php

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Twig_SimpleFilter;
use Timber\URLHelper;



$bundle_folder = 'static';
$template_dir = get_template_directory();
$bundle_path = $template_dir . "/$bundle_folder";
$assets_path = get_stylesheet_directory_uri() . "/assets";

$loader = new FilesystemLoader($template_dir . '/views');
$loader->addPath($bundle_path, 'static');
$loader->addPath($template_dir . '/views', 'PathViews');

$twig = new Environment($loader, [
    'debug' => true,
    'cache' => false, // evaluate in production if enable cache
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addGlobal('theme', $template_dir);
$twig->addGlobal('assets', $assets_path);

$function = new TwigFunction('revisions', function ($file, $manifest_name = 'rev-manifest.json') use ($bundle_folder) {
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
});

$twig->addFunction($function);

$custom_wp_head = new TwigFunction('custom_wp_head', function () {
    return wp_head();
});
$twig->addFunction($custom_wp_head);
//add_filter( 'timber/twig', 'add_to_twig' );

$custom_wp_footer = new TwigFunction('custom_wp_footer', function () {
    return wp_footer();
});
$twig->addFunction($custom_wp_footer);

//function add_to_twig( $twig ) {

$twig->addFilter(new Timber\Twig_Filter('trans', function ($value, $params = [], $lang = false) {
    if ($lang) {
        //$value = apply_filters( 'wpml_translate_single_string', $value, PROJECT_TEXT_DOMAIN, 'String label', $lang );
        global $sitepress;
        $current_lang = $sitepress->get_current_language();
        $sitepress->switch_lang($lang);
        $value = __($value, PROJECT_TEXT_DOMAIN);
        $sitepress->switch_lang($current_lang);
    } else {
        $value = __($value, PROJECT_TEXT_DOMAIN);
    }
    if (count($params)) {
        return vsprintf($value, $params);
    } else {
        return $value;
    }
}));

$twig->addFilter(new Timber\Twig_Filter('is_current_url', function ($link) {
    return (URLHelper::get_current_url() == $link) ? true : false;
}));

$current_url = new TwigFunction('current_url', function () {
    return URLHelper::get_current_url();
});
$twig->addFunction($current_url);

return $twig;
