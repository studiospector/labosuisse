<?php

/**
 * Removes buttons from the first row of the tiny mce editor.
 *
 * @see     http://thestizmedia.com/remove-buttons-items-wordpress-tinymce-editor/
 *
 * @param array $buttons The default array of buttons
 *
 * @return array The updated array of buttons that exludes some items
 */
// add_filter('mce_buttons', 'caffeina_remove_button_wp_editor');
// function caffeina_remove_button_wp_editor($buttons)
// {
//     $remove_buttons = array(
//         // 'bold',
//         // 'italic',
//         'formatselect',
//         'strikethrough',
//         'bullist',
//         'numlist',
//         'blockquote',
//         'hr', // horizontal line
//         'alignleft',
//         'aligncenter',
//         'alignright',
//         //'link',
//         //'unlink',
//         'wp_more', // read more link
//         'spellchecker',
//         'dfw', // distraction free writing mode
//         'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
//     );
//     foreach ($buttons as $button_key => $button_value) {
//         if (in_array($button_value, $remove_buttons)) {
//             unset($buttons[$button_key]);
//         }
//     }

//     return $buttons;
// }



/*
 * Removes buttons from the second row (kitchen sink) of the tiny mce editor
 *
 * @link     http://thestizmedia.com/remove-buttons-items-wordpress-tinymce-editor/
 *
 * @param    array    $buttons    The default array of buttons in the kitchen sink
 * @return   array                The updated array of buttons that exludes some items
 */
// add_filter('mce_buttons_2', 'caffeina_remove_button_2_wp_editor');
// function caffeina_remove_button_2_wp_editor($buttons)
// {
//     $remove_buttons = array(
//         'formatselect', // format dropdown menu for <p>, headings, etc
//         'underline',
//         'alignjustify',
//         'forecolor', // text color
//         'pastetext', // paste as text
//         'removeformat', // clear formatting
//         'charmap', // special characters
//         'outdent',
//         'indent',
//         'undo',
//         'redo',
//         'wp_help', // keyboard shortcuts
//     );
//     foreach ($buttons as $button_key => $button_value) {
//         if (in_array($button_value, $remove_buttons)) {
//             unset($buttons[$button_key]);
//         }
//     }

//     return $buttons;
// }
