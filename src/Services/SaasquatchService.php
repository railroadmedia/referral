<?php

namespace Railroad\Referral\Services;

use Exception;
use Railroad\Referral\Exceptions\NotFoundException;
use Railroad\Referral\Exceptions\ReferralException;
use Railroad\Referral\Exceptions\SaasquatchException;
use Railroad\Referral\Exceptions\SaasquatchUserExistsException;
use Railroad\Referral\ExternalHelpers\SaasquatchApi;
use Railroad\Referral\Models\Structures\SaasquatchUser;

class SaasquatchService
{
    /**
     * @var SaasquatchApi
     */
    protected $saasquatchApi;

    protected $saasquatchReferralProgramId;

    /**
     * SaasquatchService constructor.
     *
     * @param SaasquatchApi $saasquatchApi
     */
    public function __construct(
        SaasquatchApi $saasquatchApi
    ) {
        $this->saasquatchApi = $saasquatchApi;
        $this->saasquatchReferralProgramId = config('referral.saasquatch_referral_program_id');
    }

    /**
     * @param int $userId
     *
     * @return SaasquatchUser|null
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function getUser($userId): ?SaasquatchUser
    {
        try {
            $userData = $this->saasquatchApi->getUser($userId);

            return $this->hydrateSaasquatchUser($userData);
        } catch (NotFoundException $ex) {
            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $userId
     *
     * @return SaasquatchUser
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function createOrGetUser($userId): SaasquatchUser
    {
        try {
            $userData = $this->saasquatchApi->createUser($userId);

            return $this->hydrateSaasquatchUser($userData);
        } catch (SaasquatchUserExistsException $ex) {
            return $this->getUser($userId);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param object $userData
     *
     * @return SaasquatchUser
     */
    public function hydrateSaasquatchUser($userData): SaasquatchUser
    {
        $userId = $userData->id;

        $referralCode = $userData->referralCodes->{$this->saasquatchReferralProgramId};
        $referralLink = $userData->programShareLinks->{$this->saasquatchReferralProgramId}->cleanShareLink;

        return new SaasquatchUser($userId, $this->saasquatchReferralProgramId, $referralCode, $referralLink);
    }
}
