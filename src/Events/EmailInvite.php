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
     * EmailInvite constructor.
     * @param  string  $receiversEmail
     * @param  string  $referralLink
     */
    public function __construct(string $receiversEmail, string $referralLink)
    {
        $this->receiversEmail = $receiversEmail;
        $this->referralLink = $referralLink;
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
}
