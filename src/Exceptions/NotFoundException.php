<?php

namespace Railroad\Referral\Exceptions;

class NotFoundException extends ReferralException
{
    /**
     * NotFoundException constructor.
     *
     * @param string $message
     */
    public function __construct($message, $code = 404)
    {
        $this->message = $message;
        $this->title = 'Not found.';
        $this->code = $code;
    }
}
