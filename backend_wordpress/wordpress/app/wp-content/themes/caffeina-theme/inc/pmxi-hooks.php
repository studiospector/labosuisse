<?php

use Caffeina\LaboSuisse\Actions\Distributor\Distributor;
use Caffeina\LaboSuisse\Actions\Store\AttachBeautySpecialistEvent;
use Caffeina\LaboSuisse\Actions\Store\UpdateGoogleMapsData;

function after_xml_import($import_id, $import)
{
    $postType = $import->options['custom_type'];

    switch ($postType) {
        case 'lb-store':
            (new UpdateGoogleMapsData($import_id))->start();
            break;
        case 'lb-beauty-specialist':
            (new AttachBeautySpecialistEvent($import_id))->start();
            break;
        case 'lb-distributor':
            (new Distributor($import_id))->update();
            break;
    }
}

add_action( 'pmxi_after_xml_import', 'after_xml_import', 10, 2 );
