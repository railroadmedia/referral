<?php

namespace Railroad\Referral\Controllers;

use Illuminate\Routing\Controller;
use Railroad\Referral\Events\EmailInvite;
use Railroad\Referral\Requests\ClaimingJoinRequest;
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
        $referalUser = $this->referralService->getOrCreateCustomer(auth()->id());

        if (!$this->referralService->canRefer($referalUser)) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['email-invite-message' => config('referral.messages.email_invite_fail')]);
        }

        event(new EmailInvite(auth()->id(), $referalUser->user_referral_link, $request->get('email')));

        $redirect = $request->has('redirect') ? $request->get('redirect') : url()->route(config('referral.email_invite_route'));

        return redirect()
            ->away($redirect)
            ->with(['email-invite-message' => config('referral.messages.email_invite_success')]);
    }

    /**
     * @param EmailInviteRequest $request
     *
     * @return Fractal
     */
    public function claimingJoin(ClaimingJoinRequest $request)
    {
        // todo - raise event to create user then redirect to members or back with errors
    }
}
