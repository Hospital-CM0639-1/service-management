<?php

namespace App\Common\Enum\Patient;

final class PatientGenderEnum
{
    public const MALE = 'Male';
    public const FEMALE = 'Female';
    public const OTHER = 'Other';

    public static function getAllValues(): array
    {
        return [
            self::MALE,
            self::FEMALE,
            self::OTHER,
        ];
    }
}
