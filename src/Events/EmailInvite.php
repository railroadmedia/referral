<?php

namespace Railroad\Referral\Events;

class EmailInvite
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $referralLink;

    /**
     * @var string
     */
    protected $email;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param string $referralLink
     * @param string $email
     */
    public function __construct($userId, $referralLink, $email)
    {
        $this->userId = $userId;
        $this->referralLink = $referralLink;
        $this->email = $email;
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
    public function getReferralLink(): string
    {
        return $this->referralLink;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
