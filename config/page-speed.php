<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Laravel Page Speed
    |--------------------------------------------------------------------------
    |
    | Set this field to false to disable the laravel page speed service.
    | You would probably replace that in your local configuration to get a readable output.
    |
    */
    'enable' => !env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Enable Middleware for Laravel Page Speed
    |--------------------------------------------------------------------------
    |
    | Set this field to false to disable the laravel page speed middleware.
    |
    */
    'middleware' => [
        'collapse_whitespace' => [
            'enable' => true,
            'regex' => [
                "/\n([\S])/" => '$1',
                "/\r/" => '',
                "/\n/" => '',
                "/\t/" => '',
                "/ +/" => ' ',
                "/> +</" => '><',
            ]
        ],
        'defer_javascript' => [
            'enable' => false,
            'regex' => [
                '/<script(?=[^>]+src[^>]+)((?![^>]+defer|data-pagespeed-no-defer[^>]+)[^>]+)/i' => '<script $1 defer',
            ]
        ],
        'elide_attributes' => [
            'enable' => true,
            'regex' => [
                '/ method=("get"|get)/' => '',
                '/ disabled=[^ >]*(.*?)/' => ' disabled',
                '/ selected=[^ >]*(.*?)/' => ' selected',
            ]
        ],
        'inline_css' => [
            'enable' => true,
        ],
        'insert_dns_prefetch' => [
            'enable' => true,
        ],
        'remove_comments' => [
            'enable' => false, // disabled due beta version
            'regex' => [
                // Remove HTML comments, but not conditional comments or IE hacks
                '/<!--(?!\[if|\s*\]).*?-->/s' => '',
                // Remove CSS comments, but not inside url() or data URIs
                '/\/\*(?!\!)(?:(?!url\().)*?\*\//s' => '',
                // Remove JS single-line comments, but not in URLs or inside strings
                // This is a best-effort and may not cover all edge cases
                '/(?<!https:|http:|file:|ftp:)\/\/[^\n\r]*/' => '',
                // Remove JS multi-line comments, but not inside strings or regex
                '/\/\*(?!\!)[\s\S]*?\*\//' => '',
            ]
        ],
        'remove_quotes' => [
            'enable' => true,
            'regex' => [
                '/ src="(.\S*?)"/' => ' src=$1',
                '/ width="(.\S*?)"/' => ' width=$1',
                '/ height="(.\S*?)"/' => ' height=$1',
                '/ name="(.\S*?)"/' => ' name=$1',
                '/ charset="(.\S*?)"/' => ' charset=$1',
                '/ align="(.\S*?)"/' => ' align=$1',
                '/ border="(.\S*?)"/' => ' border=$1',
                '/ crossorigin="(.\S*?)"/' => ' crossorigin=$1',
                '/ type="(.\S*?)"/' => ' type=$1',
            ]
        ],
        'trim_urls' => [
            'enable' => true,
            'regex' => '/https?:/'
        ],
        'minify_javascript' => [
            'enable' => false, // disabled due beta version
            'preserve_variables' => [], // Variables to preserve from minification
            'preserve_functions' => ['select2'], // Function names to preserve from minification
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Skip Routes
    |--------------------------------------------------------------------------
    |
    | Skip Routes paths to exclude.
    | You can use * as wildcard.
    |
    */
    'skip' => [
        '*.xml',
        '*.less',
        '*.pdf',
        '*.doc',
        '*.txt',
        '*.ico',
        '*.rss',
        '*.zip',
        '*.mp3',
        '*.rar',
        '*.exe',
        '*.wmv',
        '*.doc',
        '*.avi',
        '*.ppt',
        '*.mpg',
        '*.mpeg',
        '*.tif',
        '*.wav',
        '*.mov',
        '*.psd',
        '*.ai',
        '*.xls',
        '*.mp4',
        '*.m4a',
        '*.swf',
        '*.dat',
        '*.dmg',
        '*.iso',
        '*.flv',
        '*.m4v',
        '*.torrent',
        '*.jpg',
        '*.jpeg',
        '*.png',
        '*.gif',
        '*.webp',
        '*.svg',
        '*.bmp',
        '*.tiff',
        '*.avif',
        '*.ico',
        '*.apng',
        '*.heic',
        '*.heif',
        '*.jfif',
        '*.jxl',
    ],
];
