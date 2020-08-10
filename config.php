<?php

return [
    'vcb' => [
        'master' => [
            'api_type' => env('VCB_TYPE', null),
            'api_url' => env('VCB_MASTER_URL', null),
            'merchant_id' => env('VCB_MERCHANT_ID', null),
            'api_key' => env('VCB_KEY', null),
            'secret_key' => env('VCB_SECRET_KEY', null),
        ]
    ]
];
