<?php

return [
    // database
    'database_connection_name' => 'musora_mysql',
    'data_mode' => 'client', // 'host' or 'client', hosts do the db migrations, clients do not

    // unique user validation database info
    'database_info_for_unique_user_email_validation' => [
        'database_connection_name' => 'mysql',
        'table' => 'users',
        'email_column' => 'email',
        'phone_number_column' => 'phone_number',
    ],

    'route_prefix' => 'referral',
    'route_middleware_logged_in_groups' => [],

    'app_route_prefix' => 'api/referral',
    'app_route_middleware_logged_in_groups' => [],

    'email_invite_route' => 'members.home',

    'referrals_per_user' => 5,

    'saasquatch_api_key' => env('SAASQUATCH_API_KEY'),
    'saasquatch_tenant_alias' => env('SAASQUATCH_TENANT_ALIAS'),
    'saasquatch_referral_program_id' => '',
    'saasquatch_program_share_links' => 'drumeo-30-day-referral-test',

    'messages' => [
        'email_invite_success' => 'Invitation sent successfully',
        'email_invite_fail' => 'Maximum referrals reached',
    ],
];
