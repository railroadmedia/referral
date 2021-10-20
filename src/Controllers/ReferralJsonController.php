<?php

namespace Railroad\Referral\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Railroad\Referral\Events\EmailInvite;
use Railroad\Referral\Requests\EmailInviteJsonRequest;
use Railroad\Referral\Services\ReferralService;

class ReferralJsonController extends Controller
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
     * @param EmailInviteJsonRequest $request
     * @return
     */
    public function emailInvite(EmailInviteJsonRequest $request)
    {
        // todo - add check for number of referrals user has
        $referalUser = $this->referralService->getOrCreateCustomer(auth()->id());

        event(new EmailInvite(auth()->id(), $referalUser->user_referral_link, $request->get('email')));
    }
}
