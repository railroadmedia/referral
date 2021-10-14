<?php

return [
    // database
    'database_connection_name' => 'musora_mysql',
    'data_mode' => 'client', // 'host' or 'client', hosts do the db migrations, clients do not

    'referrals_per_user' => 5,

    'saasquatch_api_key' => env('SAASQUATCH_API_KEY'),
    'saasquatch_tenant_alias' => env('SAASQUATCH_TENANT_ALIAS'),
    'saasquatch_referral_program_id' => '',
    'saasquatch_program_share_links' => 'drumeo-30-day-referral-test',
];
