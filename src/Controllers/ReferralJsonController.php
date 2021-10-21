<?php

namespace Railroad\Referral\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Railroad\Referral\Events\EmailInvite;
use Railroad\Referral\Exceptions\ReferralException;
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
     * @return JsonResponse
     */
    public function emailInvite(EmailInviteJsonRequest $request)
    {
        $referalUser = $this->referralService->getOrCreateCustomer(auth()->id());

        if (!$this->referralService->canRefer($referalUser)) {
            throw new ReferralException(config('referral.messages.email_invite_fail'));
        }

        event(new EmailInvite(auth()->id(), $referalUser->user_referral_link, $request->get('email')));

        return response()->json(['success' => true]);
    }
}
