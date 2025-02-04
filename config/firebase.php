<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */
    // 'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */
    // 'projects' => [
    //     'app' => [
            
    //         // 'credentials' => [
    //         //     'file' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),

    //         //     /*
    //         //      * If you want to prevent the auto discovery of credentials, set the
    //         //      * following parameter to false. If you disable it, you must
    //         //      * provide a credentials file.
    //         //      */
    //         //     'auto_discovery' => true,
    //         // ],
    //             'credentials' => [
    //                 'file' => env('FIREBASE_CREDENTIALS_PATH'), // ✅ Should be a valid JSON file path
    //                 'auto_discovery' => true,
    //             ],
              
        

    //         'auth' => [
    //             'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
    //         ],

           

    //         'database' => [
             
    //             'url' => env('FIREBASE_DATABASE_URL'),

        
    //         ],

    //         'dynamic_links' => [
             
           
    //             'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
    //         ],

    //         /*
    //          * ------------------------------------------------------------------------
    //          * Firebase Cloud Storage
    //          * ------------------------------------------------------------------------
    //          */

    //         'storage' => [
               

    //             'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
    //         ],

            
           

    //         'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

           

    //         'logging' => [
    //             'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
    //             'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
    //         ],

          
    //         'http_client_options' => [
                
    //             'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),
    //             'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),
    //         ],
    //     ],
    // ],
    'default' => env('FIREBASE_PROJECT', 'app'),
    'projects' => [
        'app' => [
            'credentials' => [
                'file' => storage_path(env('FIREBASE_CREDENTIALS_PATH', 'firebase/firebase_credentials.json')),
                'auto_discovery' => true,
            ],
            'database' => [
                'url' => env('FIREBASE_DATABASE_URL', 'https://eessystem-c4f73-default-rtdb.firebaseio.com/'),
            ],
        ],
    ],
];
