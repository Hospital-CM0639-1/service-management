<?php

namespace App\Common\Enum\Error\Password;

final class CommonPasswordErrorCodeEnum
{
    public const PASSWORD_001 = 'Password does not match';
    public const PASSWORD_002 = 'Password does not match constraints';
    public const PASSWORD_003 = 'Password is equals to one of last 5 passwords';
    public const PASSWORD_004 = 'Password contains invalid words';
    public const PASSWORD_005 = 'Old password is not correct';
}
