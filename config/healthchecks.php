<?php

return [
    /*
     * The base URL endpoint of healthchecks.io
     */
    'url'  => env('HEALTHCHECKS_PING_URL', 'https://hc-ping.com/'),

    /*
     * The key to be used for the ping in slug mode
     */
    'key'  => env('HEALTHCHECKS_PING_KEY', ''),

    /*
     * The log channel for ping connections errors
     */
    'log'  => env('HEALTHCHECKS_PING_LOG', 'stack'),

    /*
     * The jobs to check
     */
    'jobs' => [
        // 'my-first-check' => [
        //     'uuid' => '00000000-0000-0000-0000-000000000000', // Required only if mode is 'uuid'
        //     'slug' => 'my-first-check', // Optional, if not defined, the job name is used
        // ],
    ],
];
