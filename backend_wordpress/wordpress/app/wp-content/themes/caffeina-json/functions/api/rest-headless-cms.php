<?php

add_filter('rest_url', function ($url) {
    // removing /wp at the end of site_url
    $pattern = '/(\S+)(\/wp\/?)$/';
    $siteURL = preg_replace($pattern, '${1}', site_url());
    $url = str_replace(home_url(), $siteURL, $url);
    return $url;
});
