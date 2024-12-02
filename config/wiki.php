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
];
