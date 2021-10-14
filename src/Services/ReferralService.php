<?php

namespace Railroad\Referral\Services;

use Carbon\Carbon;
use Railroad\Referral\Contracts\UserEntityInterface;
use Railroad\Referral\Models\Customer;
use Railroad\Referral\Models\Structures\SaasquatchUser;

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
     * @param int $userId
     *
     * @return Customer
     *
     * @throws Exception
     */
    public function getCustomer(int $userId): ?Customer
    {
        /**
         * @var $customer Customer
         */
        $customer = Customer::query()->where(
            [
                'usora_id' => $userId,
            ]
        )->first();

        return $customer;
    }

    /**
     * @param int $userId
     *
     * @return Customer
     *
     * @throws Exception
     */
    public function getOrCreateCustomer(int $userId): Customer
    {
        $customer = $this->getCustomer($userId);

        if (is_null($customer)) {
            $customer = $this->createCustomer($userId);
        }

        return $customer;
    }

    /**
     * @param int $userId
     *
     * @return Customer
     *
     * @throws Exception
     */
    public function createCustomer(int $userId): Customer
    {
        $saasquatchUser = $this->saasquatchService->createOrGetUser($userId);

        $customer = new Customer();

        $customer->usora_id = $userId;
        $customer->user_referrals_performed = 0;
        $customer->user_referral_link = $saasquatchUser->getReferralLink();

        $customer->setCreatedAt(Carbon::now());
        $customer->setUpdatedAt(Carbon::now());

        $customer->saveOrFail();

        return $customer;
    }
}
