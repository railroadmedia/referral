<?php

namespace Railroad\Referral\Services;

use Carbon\Carbon;
use Railroad\Referral\Models\Referrer;

class ReferralService
{
    /**
     * @var SaasquatchService
     */
    private $saasquatchService;

    /**
     * ReferralService constructor.
     *
     * @param ReferralService $referralService
     */
    public function __construct(SaasquatchService $saasquatchService)
    {
        $this->saasquatchService = $saasquatchService;
    }

    public function getReferralsPerUser()
    {
        return config('referral.referrals_per_user');
    }

    /**
     * @param Referrer $referrer
     *
     * @return bool
     */
    public function canRefer(Referrer $referrer): bool
    {
        return $referrer->referrals_performed < $this->getReferralsPerUser();
    }

    /**
     * @param int $userId
     * @param string $referralProgramId
     *
     * @return Referrer
     *
     * @throws Exception
     */
    public function getReferrer(int $userId, string $referralProgramId): ?Referrer
    {
        /**
         * @var $referrer Referrer
         */
        $referrer = Referrer::query()->where(
            [
                'user_id' => $userId,
                'referral_program_id' => $referralProgramId,
            ]
        )->first();

        return $referrer;
    }

    /**
     * @param  int  $userId
     * @param  string  $referralProgramId
     * @return Referrer
     */
    public function getOrCreateReferrer(int $userId, string $referralProgramId): Referrer
    {
        $referrer = $this->getReferrer($userId, $referralProgramId);

        if (is_null($referrer)) {
            $referrer = $this->createReferrer($userId, $referralProgramId);
        }

        return $referrer;
    }

    /**
     * @param  int  $userId
     * @param  string  $programId
     * @return Referrer
     */
    public function createReferrer(int $userId, string $referralProgramId): Referrer
    {
        $saasquatchUser = $this->saasquatchService->createOrGetUser($userId);

        $referrer = new Referrer();

        $referrer->user_id = $userId;
        $referrer->referral_program_id = $saasquatchUser->getReferralProgramId();
        $referrer->referral_code = $saasquatchUser->getReferralCode();
        $referrer->referral_link = $saasquatchUser->getReferralLink();
        $referrer->referrals_performed = 0;

        $referrer->setCreatedAt(Carbon::now());
        $referrer->setUpdatedAt(Carbon::now());

        $referrer->saveOrFail();

        return $referrer;
    }
}
