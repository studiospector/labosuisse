<?php
require_once( '/var/www/html/wp-load.php' );

function getTerms()
{
    return get_terms([
        'taxonomy' => 'pa_colore',
        'hide_empty' => false,
    ]);
}

function getColor($colorId)
{
    $mapping = [
        '001' => 'E5C5A8',
        '002' => 'DBBC98',
        '003' => 'CDB494',
        '004' => 'B69B7F',
        '005' => 'A5876D',
        '006' => '937158',
        '101' => 'E5C5A8',
        '102' => 'DBBC98',
        '103' => 'D2AB84',
        '104' => 'B79C7F',
        '105' => 'A98E78',
        '106' => '927260',
        '201' => 'E7D2AA',
        '202' => 'E1BD9D',
        '203' => 'E1B592',
        '204' => 'BF977E',
        '205' => 'B59472',
        '206' => 'A7886E',
        '301' => 'E6C6A9',
        '302' => 'DEBE99',
        '303' => 'C6996F',
        '401' => 'E5C5A8',
        '402' => 'D2AB84',
        '403' => 'C5986F',
        '12' => 'c59c85',
        '13' => 'a6815d',
        '14' => 'ac8871',
        '15' => '8f683c',
        '22' => 'd9b18a',
        '23' => 'c59c85',
        '24' => 'b4886d',
        '25' => 'a5805b',
        '32' => 'e7c0a1',
        '33' => 'e7b897',
        '34' => 'd2afa0',
        '35' => 'c9a489',
        '42' => 'c59c85',
        '43' => 'bd9c7b',
        '44' => 'e7b795',
        '45' => 'cda075',
    ];

    return $mapping[$colorId] ?? null;
}

function update()
{
    $colors = getTerms();

    foreach ($colors as $color) {
        $exadecimal = getColor($color->name);
        if($exadecimal) {
            update_field('lb_product_color_taxonomy_exadecimal', "#{$exadecimal}", "pa_colore_" . $color->term_id);
        }
        echo 'update = > ' . get_field('lb_product_color_taxonomy_exadecimal',$color) . "\n";
    }
}


update();
