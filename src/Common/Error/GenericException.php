<?php

namespace App\Common\Error;

use App\Common\Enum\Error\CommonErrorCodeEnum;
use Throwable;

class GenericException extends \Exception
{
    public function __construct(string $message = CommonErrorCodeEnum::DEFAULT_400, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}