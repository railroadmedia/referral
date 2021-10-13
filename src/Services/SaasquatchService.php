<?php

namespace Railroad\Referral\Services;

use Railroad\Referral\ExternalHelpers\SaasquatchApi;

class SaasquatchService
{
    /**
     * @var SaasquatchApi
     */
    protected $saasquatchApi;

    /**
     * SaasquatchService constructor.
     *
     * @param SaasquatchApi $saasquatchApi
     */
    public function __construct(
        SaasquatchApi $saasquatchApi
    ) {
        $this->saasquatchApi = $saasquatchApi;
    }

    public function createUser($userId)
    {
        return $this->saasquatchApi->createUser($userId);
    }
}
