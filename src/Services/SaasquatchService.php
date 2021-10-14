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

    protected $programShareLinksName;

    /**
     * SaasquatchService constructor.
     *
     * @param SaasquatchApi $saasquatchApi
     */
    public function __construct(
        SaasquatchApi $saasquatchApi
    ) {
        $this->saasquatchApi = $saasquatchApi;
        $this->programShareLinksName = config('referral.saasquatch_program_share_links');
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
        $userReferralLink = $userData->programShareLinks->{$this->programShareLinksName}->cleanShareLink;

        return new SaasquatchUser($userId, $userReferralLink);
    }
}
