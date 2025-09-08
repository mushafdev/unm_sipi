<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        
        'images' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images'),
            'url' => env('APP_URL').'/images',
        ],

        'pasien_photo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images/pasien_photo'),
            'url' => env('APP_URL').'/images/pasien_photo',
        ],
        
        'foto_before' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images/foto_before'),
            'url' => env('APP_URL').'/images/foto_before',
        ],
        
        'soal_gambar' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images/soal_gambar'),
            'url' => env('APP_URL').'/images/soal_gambar',
        ],
        'users_photo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images/users_photo'),
            'url' => env('APP_URL').'/images/users_photo',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        // public_path('storage') => storage_path('app/public'),
        public_path('files') => storage_path('app/public/files'),
        public_path('images') => storage_path('app/public/images'),
    ],

];
