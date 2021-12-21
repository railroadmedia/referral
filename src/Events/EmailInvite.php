<?php

namespace Railroad\Referral\Events;

class EmailInvite
{
//    /**
//     * @var int
//     */
//    protected $userId;

    /**
     * @var string
     */
    protected $referralLink;

    /**
     * @var string
     */
    protected $email;

//    /**
//     * @var string
//     */
//    protected $referralProgramId;

//    /**
//     * @var string
//     */
//    protected $referralCustomerIOEventName;

    /**
     * Create a new event instance.
     *
//     * @param int $userId
     * @param string $referralLink
     * @param string $email
//     * @param string $referralProgramId

     */
//    public function __construct($userId, $referralLink, $email, $referralProgramId)
//    public function __construct($userId, $referralLink, $email, $referralCustomerIOEventName)
    public function __construct($referralLink, $email)
        {
//        $this->userId = $userId;
        $this->referralLink = $referralLink;
        $this->email = $email;
//        $this->referralCustomerIOEventName = $referralCustomerIOEventName;
    }

    //todo: update update class; we do not need all the params!  // update: done

//    /**
//     * @return int
//     */
//    public function getUserId(): int
//    {
//        return $this->userId;
//    }

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
//
//    /**
//     * @return string
//     */
//    public function getReferralProgramId(): string
//    {
//        return $this->referralProgramId;
//    }

//    /**
//     * @return string
//     */
//    public function getReferralCustomerIOEventName(): string
//    {
//        return $this->referralCustomerIOEventName;
//    }
}
