<?php

// function getMenu($slug)
// {
//     // language mapping
//     $lang_map = [
//         'Italiano' => 'it',
//         'English'  => 'en',
//     ];

//     // get current language as "en-EN" format and make it "EN" format
//     $context = Timber::context();
//     $full_language = $context['site']->language;
//     $language = substr($full_language, 0, strpos($full_language, "-"));    

//     // get menu
//     $full_menu = new Timber\Menu($slug);

//     foreach($full_menu->items as $item)
//     {
//         $clean_title = strip_tags(trim($item->__title));
//         // if its in the mapping, save it in language field
//         if(array_key_exists($clean_title, $lang_map)){
//             $menu['lang'][] = [
//                 'title'     => strtoupper($lang_map[$clean_title]),
//                 'url'       => $item->url,
//                 'isCurrent' => $lang_map[$clean_title] == $language,
//             ];
//         }
//         else{
//             $menu['items'][] = [
//                 'title' => $clean_title,
//                 'url'   => $item->url,
//             ];
//         }

//     }

//     return $menu;
// }
