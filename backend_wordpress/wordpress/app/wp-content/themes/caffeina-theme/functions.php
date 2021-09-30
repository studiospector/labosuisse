<?php



// spl_autoload_register( function($classname) {
//     //$class      = str_replace( '\\', DIRECTORY_SEPARATOR, strtolower($classname) );
//     $class      = str_replace( '\\', DIRECTORY_SEPARATOR, ($classname) );
//     $classpath  = get_template_directory() .  DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class . '.php';

//     if ( file_exists( $classpath) ) {
//         include_once $classpath;
//     }

//   } );
// *** THEME SETUP ***
add_theme_support('post-thumbnails');

include __DIR__.'/functions/setup/configure-wp-editor.php';
include __DIR__.'/functions/setup/disable-comments.php';
include __DIR__.'/functions/setup/disable-front-page-redirect.php';
include __DIR__.'/functions/setup/mime-types.php';
//include __DIR__.'/functions/setup/pagination-custom-permalink.php';
//include __DIR__.'/functions/setup/transform-permalink.php';
// include __DIR__.'/functions/setup/virtual-pages.php';
include __DIR__.'/functions/setup/composer-packages.php';



// *** THEME API ***
//include __DIR__.'/functions/api/rest-headless-cms.php';
//nclude __DIR__.'/functions/api/menus.php';
//include __DIR__.'/functions/api/options.php';

// *** THEME UTILS ***
//include __DIR__.'/functions/utils/get-meta-tags-from-string.php';
include __DIR__.'/functions/utils/json-response.php';
include __DIR__.'/functions/utils/soft-trim.php';
include __DIR__.'/functions/utils/transform-image-to-figure.php';
include __DIR__.'/functions/utils/truncate-html.php';

