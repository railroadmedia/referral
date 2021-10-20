<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('referral.app_route_prefix'),
    'middleware' => config('referral.app_route_middleware_logged_in_groups'),
], function () {

    Route::post(
        '/email-invite',
        Railroad\Referral\Controllers\ReferralJsonController::class . '@emailInvite'
    )->name('referral.email-invite');

});
