<?php

namespace Railroad\Referral\Services;

use Railroad\Referral\Contracts\UserEntityInterface;
use Railroad\Referral\Models\Customer;

class ReferralService
{
    /**
     * @param UserEntityInterface $user
     *
     * @return int
     *
     * @throws Exception
     */
    public function getUserReferralsLeft(
        UserEntityInterface $user
    ): int {
        $customer = $this->getOrCreateCustomer($user);

        $left = config('referral.referrals_per_user') - $customer->user_referrals_performed;

        return $left >= 0 ? $left : 0;
    }

    /**
     * @param UserEntityInterface $user
     *
     * @return string
     *
     * @throws Exception
     */
    public function getUserReferralLink(
        UserEntityInterface $user
    ): int {
        // todo - update

        return '';
    }

    /**
     * @param UserEntityInterface $user
     *
     * @return Customer
     *
     * @throws Exception
     */
    public function getCustomer(UserEntityInterface $user): ?Customer {
        /**
         * @var $customer Customer
         */
        $customer = Customer::query()->where(
            [
                'usora_id' => $user->getId(),
            ]
        )->first();
    }

    /**
     * @param UserEntityInterface $user
     *
     * @return Customer
     *
     * @throws Exception
     */
    public function getOrCreateCustomer(UserEntityInterface $user): Customer {
        $customer = $this->getCustomer($user);

        if (is_null($customer)) {
            $customer = $this->createCustomer($user);
        }

        return $customer;
    }

    /**
     * @param UserEntityInterface $user
     *
     * @return Customer
     *
     * @throws Exception
     */
    public function createCustomer(UserEntityInterface $user): Customer {
        $customer = new Customer();

        $customer->internal_id = $userId;
        $customer->user_referrals_performed = 0;

        $customer->setCreatedAt(Carbon::now());
        $customer->setUpdatedAt(Carbon::now());

        $customer->saveOrFail();

        // todo - add calls to saasquatch

        return $customer;
    }
}
