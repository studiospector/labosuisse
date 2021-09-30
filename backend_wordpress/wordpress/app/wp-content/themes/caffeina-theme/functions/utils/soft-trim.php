<?php

function soft_trim($text, $count, $wrapText = '...')
{
    if (strlen($text) > $count) {
        preg_match('/^.{0,'.$count.'}(?:.*?)\b/siu', $text, $matches);
        $text = $matches[0];
    } else {
        $wrapText = '';
    }

    return $text.$wrapText;
}
