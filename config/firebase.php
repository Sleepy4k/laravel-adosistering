<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Project Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials and settings required to
    | connect to your Firebase project. You can set the project ID,
    | private key, client email, and other necessary configurations here.
    |
    */

    'app_id' => env('FIREBASE_APP_ID', 'your-app-id'),

    'api_key' => env('FIREBASE_API_KEY', 'your-api-key'),

    'project_id' => env('FIREBASE_PROJECT_ID', 'your-project-id'),

    'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'your-auth-domain.firebaseapp.com'),

    'database_url' => env('FIREBASE_DATABASE_URL', 'https://your-database-name.firebaseio.com'),

    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'your-storage-bucket.appspot.com'),

    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', 'your-messaging-sender-id'),
];
