<?php

namespace Railroad\Referral\Models\Structures;

class SaasquatchUser
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $referralLink;

    public function __construct($userId, $referralLink)
    {
        $this->id = $userId;
        $this->referralLink = $referralLink;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getReferralLink(): ?string
    {
        return $this->referralLink;
    }

    /**
     * @param string $referralLink
     */
    public function setReferralLink(string $referralLink)
    {
        $this->referralLink = $referralLink;
    }
}
