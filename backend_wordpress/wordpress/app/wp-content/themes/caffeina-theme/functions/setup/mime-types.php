<?php

function allow_svg($mimes)
{
    // New allowed mime types.
    $mimes['svg'] = 'image/svg';
    $mimes['svgz'] = 'image/svg';

    return $mimes;
}

add_filter('upload_mimes', 'allow_svg');
