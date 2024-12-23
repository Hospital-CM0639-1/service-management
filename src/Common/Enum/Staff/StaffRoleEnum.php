<?php

namespace App\Common\Enum\Staff;

final class StaffRoleEnum
{
    public const DOCTOR = 'DOCTOR';
    public const NURSE = 'NURSE';
    public const SECRETARY = 'SECRETARY';

    public static function getAllStaffRoles(): array
    {
        return [
            self::DOCTOR,
            self::NURSE,
            self::SECRETARY
        ];
    }
}
