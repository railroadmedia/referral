<?php

namespace Railroad\Referral\Exceptions;

class SaasquatchException extends ReferralException
{
    protected $message;

    /**
     * SaasquatchException constructor.
     *
     * @param string $message
     */
    public function __construct($message, $code = 503)
    {
        $this->message = $message;
        $this->title = 'Saasquatch Exception';
        $this->code = $code;
    }
}
