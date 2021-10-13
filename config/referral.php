<?php

return [
    // database
    'database_connection_name' => 'musora_mysql',
    'data_mode' => 'client', // 'host' or 'client', hosts do the db migrations, clients do not

    'referrals_per_user' => 5,

    'saasquatch_api_key' => env('SAASQUATCH_API_KEY'),
    'saasquatch_tenant_alias' => env('SAASQUATCH_TENANT_ALIAS'),
];
