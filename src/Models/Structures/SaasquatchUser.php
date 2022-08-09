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

    /**
     * @var string
     */
    private $brand;

    public function __construct($userId, $referralProgramId, $referralCode, $referralLink, $brand)
    {
        $this->userId = $userId;
        $this->referralProgramId = $referralProgramId;
        $this->referralCode = $referralCode;
        $this->referralLink = $referralLink;
        $this->brand = $brand;
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
        return $this->referralProgramId[$this->brand];
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

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }


}
