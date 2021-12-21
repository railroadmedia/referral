<?php

namespace Railroad\Referral\Events;

class EmailInvite
{
    /**
     * @var string
     */
    protected $receiversEmail;

    /**
     * @var string
     */
    protected $referralLink;

    /**
     * @var string
     */
    private $brand;

    /**
     * EmailInvite constructor.
     * @param  string  $receiversEmail
     * @param  string  $referralLink
     * @param  string  $brand
     */
    public function __construct(string $receiversEmail, string $referralLink, string $brand)
    {
        $this->receiversEmail = $receiversEmail;
        $this->referralLink = $referralLink;
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getReceiversEmail(): string
    {
        return $this->receiversEmail;
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
}
