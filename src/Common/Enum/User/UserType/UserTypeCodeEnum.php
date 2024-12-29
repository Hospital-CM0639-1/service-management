<?php

namespace App\Common\Enum\User\UserType;

final class UserTypeCodeEnum
{
    public const ADMIN = 'admin';
    public const API = 'api';
    public const STAFF = 'staff';
    public const PATIENT = 'patient';

    public static function getAllValues(): array
    {
        return [
            self::ADMIN,
            self::API,
            self::STAFF,
            self::PATIENT,
        ];
    }

    /**
     * Get visible user type codes to given user type
     *
     * @param string $userType
     * @return string[]
     */
    public static function getTypesVisibleToType(string $userType): array
    {
        return match ($userType) {
            self::ADMIN => [
                self::ADMIN,
                self::STAFF,
                self::PATIENT,
            ],
            self::STAFF => [
                self::PATIENT
            ]
        };
    }

    public static function getApiValues(): array
    {
        return [
            self::API
        ];
    }

    public static function convertToItalian(string $value): string
    {
        return match ($value) {
            self::ADMIN => 'Admin',
            self::API => 'API',
            self::STAFF => 'Staff',
            self::PATIENT => 'Patient',
        };
    }
}
