<?php

use Illuminate\Support\Facades\Route;

// note: these endpoints support web or json requests
Route::group(
    [
        'prefix' => config('referral.route_prefix'),
        'middleware' => config('referral.route_middleware_logged_in_groups'),
    ],
    function () {
        Route::post(
            '/email-invite',
            Railroad\Referral\Controllers\ReferralController::class.'@emailInvite'
        )->name('referral.email-invite');
    }
);

Route::post(
    '/claiming-join',
    Railroad\Referral\Controllers\ReferralController::class.'@claimingJoin'
)->name('referral.claiming-join');