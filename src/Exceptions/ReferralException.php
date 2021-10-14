<?php

namespace Railroad\Referral\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ReferralException extends Exception
{
    protected $message;
    protected $title;
    protected $code;

    /**
     * ReferralException constructor.
     *
     * @param string $message
     */
    public function __construct($message, $code = 500)
    {
        $this->message = $message;
        $this->title = 'Referral Exception';
        $this->code = $code;
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json(
            [
                'errors' => [
                    [
                        'title' => $this->title,
                        'detail' => $this->message,
                    ]
                ],
                'code' => $this->code
            ],
        );
    }
}
