<?php

function setSettings()
{
    $settings = getOriginaleSettings();

    $settings->{'dbcache.memcached.servers'}[0] = getenv('MEMCACHED_URL');
    $settings->{'pgcache.memcached.servers'}[0] = getenv('MEMCACHED_URL');
    $settings->{'objectcache.memcached.servers'}[0] = getenv('MEMCACHED_URL');
    $settings->{'minify.memcached.servers'}[0] = getenv('MEMCACHED_URL');

    $settings->{'pgcache.mirrors.enabled'} = true;
    $settings->{'pgcache.mirrors.home_urls'}[0] =  getenv('ADMIN_URL');
    $settings->{'pgcache.mirrors.home_urls'}[1] =  getenv('FRONTEND_URL');

    save($settings);
}

function getOriginaleSettings()
{
    return json_decode(
        str_replace('<?php exit; ?>', '', file_get_contents('./master.php'))
    );
}


function save($settings)
{
    $content = '<?php exit; ?>' . json_encode($settings,JSON_PRETTY_PRINT);

    file_put_contents('./master.php', $content);
}

setSettings();
