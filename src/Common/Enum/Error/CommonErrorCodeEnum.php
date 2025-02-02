<?php

namespace App\Common\Enum\Error;

use App\Common\Enum\Error\Password\CommonPasswordErrorCodeEnum;
use App\Common\Enum\Error\User\CommonUserErrorCodeEnum;

final class CommonErrorCodeEnum
{
    public const DEFAULT_000 = 'DEFAULT_000';
    public const DEFAULT_001 = 'One or more parameters are empty';
    public const DEFAULT_002 = 'Invalid credentials';

    public const DEFAULT_500 = 'Internal server error';
    public const DEFAULT_405 = 'Invalid http method';
    public const DEFAULT_404 = 'Resource not found';
    public const DEFAULT_403 = 'Forbidden';
    public const DEFAULT_401 = 'Expired session';
    public const DEFAULT_400 = 'Bad Request';



    public const PASSWORD_001 = CommonPasswordErrorCodeEnum::PASSWORD_001;
    public const PASSWORD_002 = CommonPasswordErrorCodeEnum::PASSWORD_002;
    public const PASSWORD_003 = CommonPasswordErrorCodeEnum::PASSWORD_003;
    public const PASSWORD_004 = CommonPasswordErrorCodeEnum::PASSWORD_004;
    public const PASSWORD_005 = CommonPasswordErrorCodeEnum::PASSWORD_005;



    public const USER_001 = CommonUserErrorCodeEnum::USER_001;
    public const USER_002 = CommonUserErrorCodeEnum::USER_002;
    public const USER_003 = CommonUserErrorCodeEnum::USER_003;
    public const USER_004 = CommonUserErrorCodeEnum::USER_004;
}
