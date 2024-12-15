<?php

namespace App\Common\Enum\User\UserType;

final class UserTypeCodeEnum
{
    public const ADMIN = 'admin';
    public const API = 'api';
    public const STAFF = 'staff';

    public static function getAllValues(): array
    {
        return [
            self::ADMIN,
            self::API,
            self::STAFF,
        ];
    }

    public static function convertToItalian(string $value): string
    {
        return match ($value) {
            self::ADMIN => 'Admin',
            self::API => 'API',
            self::STAFF => 'Staff',
        };
    }
}
