<?php

namespace App\Common\Enum\Patient;

final class PatientGenderEnum
{
    public const MALE = 'Male';
    public const FEMALE = 'Female';

    public static function getAllValues(): array
    {
        return [
            self::MALE,
            self::FEMALE
        ];
    }
}
