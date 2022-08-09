<?php

return [
    // database
    'database_connection_name' => 'musora_laravel_mysql',  // musora_mysql for old platform
    'data_mode' => 'host', // 'host' or 'client', hosts do the db migrations, clients do not

    // unique user validation database info
    'database_info_for_unique_user_email_validation' => [
        'database_connection_name' => 'musora_laravel_mysql',
        'table' => 'users',
        'email_column' => 'email',
        'phone_number_column' => 'phone_number',
    ],

    'password_creation_rules' => 'confirmed|min:8|max:128',

    'route_prefix' => 'referral',
    'route_middleware_logged_in_groups' => [],
    'route_middleware_public_groups' => [],

    'email_invite_redirect_route' => 'members.home',
    'claim_redirect_route' => 'members.home',

    'referrals_per_user' => 5,

    'saasquatch_api_key' => env('SAASQUATCH_API_KEY'),
    'saasquatch_tenant_alias' => env('SAASQUATCH_TENANT_ALIAS'),
    'saasquatch_referral_program_id' => [
        'drumeo' => env('SAASQUATCH_CURRENT_PROGRAM_ID_DRUMEO', 'unified-local-drumeo-test'),
        'pianote' => env('SAASQUATCH_CURRENT_PROGRAM_ID_PIANOTE', 'unified-local-pianote-test'),
        'guitareo' => env('SAASQUATCH_CURRENT_PROGRAM_ID_GUITAREO', 'unified-local-guitareo-test'),
        'singeo' => env('SAASQUATCH_CURRENT_PROGRAM_ID_SINGEO', 'unified-local-singeo-test'),
    ],

    'messages' => [
        'email_invite_success' => 'Invitation sent successfully',
        'email_invite_fail' => 'Maximum referrals reached'
    ],

    // once claimed, this product is assigned to the claiming user for the amount of days
    'referral_program_product_sku' => 'my-product-sku',
    'referral_program_product_free_days' => 30,

    // google reCaptcha, use this in form validation
    'recaptcha_site_secret' => '',
];
