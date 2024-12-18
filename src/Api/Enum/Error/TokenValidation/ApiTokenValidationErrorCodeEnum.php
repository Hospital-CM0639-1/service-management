<?php

namespace App\Api\Enum\Error\TokenValidation;

final class ApiTokenValidationErrorCodeEnum
{
    public const TOKEN_VALIDATION_001 = 'No token provided';
    public const TOKEN_VALIDATION_002 = 'Invalid token';
    public const TOKEN_VALIDATION_003 = 'Expired token';
    public const TOKEN_VALIDATION_004 = 'No user found';
    public const TOKEN_VALIDATION_005 = 'User not active';
}
