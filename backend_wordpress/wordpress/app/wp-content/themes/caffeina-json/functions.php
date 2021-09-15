<?php

// *** THEME SETUP ***
add_theme_support('post-thumbnails');
add_filter('use_block_editor_for_post', '__return_false');
include __DIR__.'/functions/setup/configure-wp-editor.php';
include __DIR__.'/functions/setup/disable-comments.php';
include __DIR__.'/functions/setup/disable-front-page-redirect.php';
include __DIR__.'/functions/setup/mime-types.php';
include __DIR__.'/functions/setup/pagination-custom-permalink.php';
include __DIR__.'/functions/setup/transform-permalink.php';
// include __DIR__.'/functions/setup/virtual-pages.php';

// *** THEME API ***
include __DIR__.'/functions/api/rest-headless-cms.php';
include __DIR__.'/functions/api/menus.php';
include __DIR__.'/functions/api/options.php';

// *** THEME UTILS ***
include __DIR__.'/functions/utils/get-meta-tags-from-string.php';
include __DIR__.'/functions/utils/json-response.php';
include __DIR__.'/functions/utils/soft-trim.php';
include __DIR__.'/functions/utils/transform-image-to-figure.php';
include __DIR__.'/functions/utils/truncate-html.php';
