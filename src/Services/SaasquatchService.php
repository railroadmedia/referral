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
     * @param  SaasquatchApi  $saasquatchApi
     */
    public function __construct(
        SaasquatchApi $saasquatchApi
    ) {
        $this->saasquatchApi = $saasquatchApi;
        $this->saasquatchReferralProgramId = config('referral.saasquatch_referral_program_id');
    }

    /**
     * @param  int  $userId
     *
     * @return SaasquatchUser|null
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function getUser($userId, $brand): ?SaasquatchUser
    {
        try {
            $userData = $this->saasquatchApi->getUser($userId);
            return $this->hydrateSaasquatchUser($userData, $brand);
        } catch (NotFoundException $ex) {
            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param  int  $userId
     *
     * @return SaasquatchUser
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function createOrGetUser($userId, $brand): SaasquatchUser
    {
        $userData = $this->getUser($userId, $brand);

        if (empty($userData)) {
            $userData = $this->hydrateSaasquatchUser($this->saasquatchApi->createUser($userId), $brand);
        }

        return $userData;
    }

    /**
     * @param $userId
     * @param $referralCode
     * @return bool
     * @throws NotFoundException
     * @throws ReferralException
     * @throws SaasquatchException
     * @throws SaasquatchUserExistsException
     */
    public function applyReferralCode($userId, $referralCode, $brand)
    {
        $saasquatchUser = $this->createOrGetUser($userId, $brand);

        $this->saasquatchApi->applyReferralCode($userId, $referralCode);

        return true;
    }

    /**
     * @param  object  $userData
     *
     * @return SaasquatchUser
     */
    public function hydrateSaasquatchUser($userData, $brand): SaasquatchUser
    {

        $userId = $userData->id;
        /* from various tests, it seems $userData->referralCodes returns max 10 program ids, even though there might be more than 10 active programs */
        // $referralCode = $userData->referralCodes->{$this->saasquatchReferralProgramId[$brand]};
        // $referralLink = $userData->programShareLinks->{$this->saasquatchReferralProgramId[$brand]}->cleanShareLink;


        /* for this reason, we use a GraphQL query to retrieve data using upsertUser call */
        $referralCode = $this->saasquatchApi->upsertUserUsingGraphqlAPI($userId)->data->upsertUser->referralCodes->{$this->saasquatchReferralProgramId[$brand]};
        $referralLink = $this->saasquatchApi->getShareUrlsFromUser($userId, $brand)->shareLinks->cleanShareLink;

        return new SaasquatchUser($userId, $this->saasquatchReferralProgramId, $referralCode, $referralLink, $brand);
    }
}
