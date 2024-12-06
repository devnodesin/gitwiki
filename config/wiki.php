<?php

return [
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
    'footer_copyright' => env('WIKI_FOOTER_COPYRIGHT', 'Copyright &copy; '.date('Y')." <a target='_blank' class='link-dark link-offset-2' href='https://github.com/devnodesin/gitwiki'>Git Wiki and Open Source by Devnodes.in</a>"),
];
