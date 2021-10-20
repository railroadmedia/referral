<?php

namespace Railroad\Referral\Controllers;

use Illuminate\Routing\Controller;
use Railroad\Referral\Events\EmailInvite;
use Railroad\Referral\Requests\EmailInviteRequest;
use Railroad\Referral\Services\ReferralService;

class ReferralController extends Controller
{
    /**
     * @var ReferralService
     */
    private $referralService;

    /**
     * @param ReferralService $referralService
     */
    public function __construct(
        ReferralService $referralService
    ) {
        $this->referralService = $referralService;
    }

    /**
     * @param EmailInviteRequest $request
     *
     * @return Fractal
     */
    public function emailInvite(EmailInviteRequest $request)
    {
        // todo - add check for number of referrals user has
        $referalUser = $this->referralService->getOrCreateCustomer(auth()->id());

        event(new EmailInvite(auth()->id(), $referalUser->user_referral_link, $request->get('email')));


    }
}
