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
//     * @param  string  $referralProgramId
     * @return Referrer
     */
    public function getOrCreateReferrer(int $userId, string $referralProgramId): Referrer
//    public function getOrCreateReferrer(int $userId): Referrer
        {

//            $referralProgramId =  env('SAASQUATCH_API_KEY');
//        $referralProgramId =  config('referral.saasquatch_api_key');  //todo: is this api_key or referral_program_id?? // update: is referral_program_id

//var_dump($referralProgramId);
//die("referral-service-8");
//        env('APP_ENV') .
//        '.providers.facebook-pixel.pixel-id'
        $referrer = $this->getReferrer($userId, $referralProgramId); // din ceva motiv nu creaza aici referrer  // update: is ok now

//var_dump();
        if (is_null($referrer)) {
            $referrer = $this->createReferrer($userId, $referralProgramId);
        }
var_dump($referrer);
die("referral-servicemircea2");
        return $referrer;
    }

    /**
     * @param  int  $userId
//     * @param  string  $programId
     * @return Referrer
     */
//    public function createReferrer(int $userId, string $referralProgramId): Referrer
    public function createReferrer(int $userId): Referrer
        {
        $saasquatchUser = $this->saasquatchService->createOrGetUser($userId);
//var_dump($saasquatchUser);
//die("referral-servce-2");

        $referrer = new Referrer();

        $referrer->user_id = $userId;
        $referrer->referral_program_id = $saasquatchUser->getReferralProgramId();
        $referrer->referral_code = $saasquatchUser->getReferralCode();
        $referrer->referral_link = $saasquatchUser->getReferralLink(); //aici
        $referrer->referrals_performed = 0;

        $referrer->setCreatedAt(Carbon::now());
        $referrer->setUpdatedAt(Carbon::now());

        $referrer->saveOrFail();

        return $referrer;
    }
}
