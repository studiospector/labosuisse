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
        '001' => ['code' => 'E5C5A8', 'name' => 'Almond'],
        '002' => ['code' => 'DBBC98', 'name' => 'Cashews'],
        '003' => ['code' => 'CDB494', 'name' => 'Nut'],
        '004' => ['code' => 'B69B7F', 'name' => 'Gingerbread'],
        '005' => ['code' => 'A5876D', 'name' => 'Muscovado'],
        '006' => ['code' => '937158', 'name' => 'Chocolate'],
        '101' => ['code' => 'E5C5A8', 'name' => 'Cream'],
        '102' => ['code' => 'DBBC98', 'name' => 'Sesame'],
        '103' => ['code' => 'D2AB84', 'name' => 'Toffee'],
        '104' => ['code' => 'B79C7F', 'name' => 'Macchiato'],
        '105' => ['code' => 'A98E78', 'name' => 'Anice'],
        '106' => ['code' => '927260', 'name' => 'Chestnut'],
        '201' => ['code' => 'E7D2AA', 'name' => 'Marzipan'],
        '202' => ['code' => 'E1BD9D', 'name' => 'Nutmeg'],
        '203' => ['code' => 'E1B592', 'name' => 'Walnut'],
        '204' => ['code' => 'BF977E', 'name' => 'Caramel'],
        '205' => ['code' => 'B59472', 'name' => 'Pecan'],
        '206' => ['code' => 'A7886E', 'name' => 'Cocoa'],
        '301' => ['code' => 'E6C6A9', 'name' => 'Soy'],
        '302' => ['code' => 'DEBE99', 'name' => 'Peanut'],
        '303' => ['code' => 'C6996F', 'name' => 'Cinnamon'],
        '401' => ['code' => 'E5C5A8', 'name' => 'Vanilla'],
        '402' => ['code' => 'D2AB84', 'name' => 'Ginger'],
        '403' => ['code' => 'C5986F', 'name' => 'Cappuccino'],
        '12' => ['code' => 'c59c85', 'name' => 'Light Rose'],
        '13' => ['code' => 'a6815d', 'name' => 'Medium Beige'],
        '14' => ['code' => 'ac8871', 'name' => 'Medium Rose'],
        '15' => ['code' => '8f683c', 'name' => 'Warm Beige'],
        '22' => ['code' => 'd9b18a', 'name' => 'Rosy Beige'],
        '23' => ['code' => 'c59c85', 'name' => 'Natural'],
        '24' => ['code' => 'b4886d', 'name' => 'Rosy Sand'],
        '25' => ['code' => 'a5805b', 'name' => 'Caramel'],
        '32' => ['code' => 'e7c0a1', 'name' => 'Peach Rose'],
        '33' => ['code' => 'e7b897', 'name' => 'Natural'],
        '34' => ['code' => 'd2afa0', 'name' => 'Apricot'],
        '35' => ['code' => 'c9a489', 'name' => 'Cinnamon'],
        '42' => ['code' => 'c59c85', 'name' => 'Pale Rose'],
        '43' => ['code' => 'bd9c7b', 'name' => 'Nude'],
        '44' => ['code' => 'e7b795', 'name' => 'Rosy Sand'],
        '45' => ['code' => 'cda075', 'name' => 'Honey'],
    ];

    return $mapping[$colorId] ?? null;
}

function update()
{
    $colors = getTerms();

    foreach ($colors as $color) {
        $colorInfo = getColor($color->name);

        if($colorInfo) {
            update_field('lb_product_color_taxonomy_hexadecimal', "{$colorInfo['code']}", "pa_colore_" . $color->term_id);
            update_field('lb_product_color_taxonomy_color_name', "{$colorInfo['name']}", "pa_colore_" . $color->term_id);
        }
        echo 'update = > ' . get_field('lb_product_color_taxonomy_hexadecimal',$color->term_id) . "\n";
        echo 'update = > ' . get_field('lb_product_color_taxonomy_color_name',$color->term_id) . "\n";
    }
}


update();
