<?php

return [
    'ttl' => 13140000, // 5 months in seconds
    'prefix' => 'cacheable',
    'identifier' => 'id',
    'logging' => [
        'channel' => 'cacheable',
        'enabled' => config('app.debug'),
        'level' => 'debug',
    ],
];
