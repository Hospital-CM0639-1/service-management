<?php

namespace App\Common\Enum\User\UserType;

final class UserTypeCodeEnum
{
    public const ADMIN = 'admin';
    public const API = 'api';

    public static function getAllValues(): array
    {
        return [
            self::ADMIN,
        ];
    }

    public static function convertToItalian(string $value): string
    {
        return match ($value) {
            self::ADMIN => 'Admin',
        };
    }
}
