<?php

namespace Railroad\Referral\Models\Structures;

class SaasquatchUser
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $referralProgramId;

    /**
     * @var string
     */
    private $referralCode;

    /**
     * @var string
     */
    private $referralLink;

    public function __construct($userId, $referralProgramId, $referralCode, $referralLink)
    {
        $this->userId = $userId;
        $this->referralProgramId = $referralProgramId;
        $this->referralCode = $referralCode;
        $this->referralLink = $referralLink;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getReferralProgramId(): string
    {
        return $this->referralProgramId;
    }

    /**
     * @return string
     */
    public function getReferralCode(): string
    {
        return $this->referralCode;
    }

    /**
     * @return string
     */
    public function getReferralLink(): string
    {
        return $this->referralLink;
    }
}
