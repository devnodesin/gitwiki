<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wiki Storage Paths
    |--------------------------------------------------------------------------
    |
    | Here you can configure the paths for storing wiki-related files.
    | These settings can be overridden using environment variables.
    |
    */

    'git_path' => env('WIKI_GIT_PATH', storage_path('git')),
    
    'pages_path' => env('WIKI_PAGES_PATH', env('WIKI_GIT_PATH', storage_path('git')) . '/pages'),
    
    'images_path' => env('WIKI_IMAGES_PATH', env('WIKI_GIT_PATH', storage_path('git')) . '/images'),

    /*
    |--------------------------------------------------------------------------
    | Wiki Access Control
    |--------------------------------------------------------------------------
    |
    | Configure whether the wiki requires user authentication.
    | When set to false, the wiki will be publicly accessible.
    |
    */

    'auth_enable' => env('WIKI_AUTH_ENABLE', true),
    'footer_copyright' => env('WIKI_FOOTER_COPYRIGHT', "Copyright &copy; " . date('Y') . " <a target='_blank' class='link-dark link-offset-2' href='https://github.com/devnodesin/gitwiki'>Git Wiki and Open Source by Devnodes.in</a>"),
];
