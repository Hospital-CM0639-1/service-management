<?php

namespace App\Common\Enum\Error;

use App\Common\Enum\Error\Password\CommonPasswordErrorCodeEnum;

final class CommonErrorCodeEnum
{
    public const DEFAULT_000 = 'DEFAULT_000';
    public const DEFAULT_001 = 'One or more parameters are empty';
    public const DEFAULT_002 = 'Invalid credentials';

    public const DEFAULT_500 = 'Internal server error';
    public const DEFAULT_404 = 'Resource not found';
    public const DEFAULT_403 = 'Forbidden';
    public const DEFAULT_401 = 'Expired session';
    public const DEFAULT_400 = 'Bad Request';
}
