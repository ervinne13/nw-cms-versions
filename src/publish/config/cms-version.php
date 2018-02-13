<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Version Detector
      |--------------------------------------------------------------------------
      |
      | This option controls how models detect which version's data to query
      | from the database.
      |
      | Supported: "request_auto", "manual"
      |
      | "request_auto"  - version will be automatically fetchd from the current request.
      | "manual"        - version should be passed via setVersion method of the VersionRecorded trait
      |
      |
     */
    'detect_version' => 'request_auto',
    /*
      |--------------------------------------------------------------------------
      | Laravel CMS Version Control
      |--------------------------------------------------------------------------
      |
      | TODO: Documentation
      |
     */
    'models' => [
        [
            'table'                => 'my_table',
            'primary_key'          => 'id',
            'classpath'            => \App\User::class,
            'is_published_field'   => 'my_is_published_field',
            'is_published_value'   => true,
            'is_unpublished_value' => false,
            'is_multiple'          => true,
            'is_migrated'          => false,
            'has_local_field'      => true,
        ],
    ],
];
