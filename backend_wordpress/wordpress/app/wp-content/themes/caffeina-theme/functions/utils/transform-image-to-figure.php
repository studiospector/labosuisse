<?php

/**
 * Filter to replace the  shortcode text with HTML5 compliant code.
 *
 * @return text HTML content describing embedded figure
 **/
function image_to_figure($val, $attr, $content = null)
{
    extract(shortcode_atts(array(
        'id' => '',
        'align' => '',
        'width' => '',
        'caption' => '',
    ), $attr));

    if (1 > (int) $width || empty($caption)) {
        return $val;
    }

    $capid = '';
    if ($id) {
        $id = esc_attr($id);
        $capid = 'id="figcaption_'.$id.'" ';
        $id = 'id="'.$id.'" aria-labelledby="figcaption_'.$id.'" ';
    }

    return '<figure '.$id.'class="wp-caption '.esc_attr($align).'">'.do_shortcode($content).'<figcaption '.$capid
    .'class="wp-caption-text">'.$caption.'</figcaption></figure>';
}

add_filter('img_caption_shortcode', 'image_to_figure', 10, 3);
