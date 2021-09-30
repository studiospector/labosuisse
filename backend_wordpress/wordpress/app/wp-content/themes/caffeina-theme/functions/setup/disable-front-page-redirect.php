<?php

function disable_front_page_redirect($redirect_url)
{
    if (is_front_page()) {
        $redirect_url = false;
    }

    return $redirect_url;
}

add_filter('redirect_canonical', 'disable_front_page_redirect');
