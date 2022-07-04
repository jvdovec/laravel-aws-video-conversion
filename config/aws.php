<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Amazon Web Services
     |--------------------------------------------------------------------------
     |
     |  Global access credentials
     |
     */
    'key' => env('AWS_ACCESS_KEY_ID', null),
    'secret' => env('AWS_SECRET_ACCESS_KEY', null),

    /*
     |--------------------------------------------------------------------------
     | AWS Elemental MediaConvert
     |--------------------------------------------------------------------------
     */
    'mediaconvert' => [
        'client_version' => env('AWS_MEDIACONVERT_CLIENT_VERSION', null),
        'region' => env('AWS_MEDIACONVERT_REGION', null),
        'iam_role_arn' => env('AWS_MEDIACONVERT_IAM_ROLE_ARN', null),
        'queue_arn' => env('AWS_MEDIACONVERT_QUEUE_ARN', null),
    ],

];
