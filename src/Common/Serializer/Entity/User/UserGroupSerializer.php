<?php

namespace App\Common\Serializer\Entity\User;

use App\Common\Serializer\Entity\Patient\PatientGroupSerializer;
use App\Common\Serializer\Entity\Staff\StaffGroupSerializer;
use App\Common\Serializer\Entity\UserType\UserTypeGroupSerializer;

final class UserGroupSerializer
{
    public static function minimalUser(): array
    {
        return ['minimalUser'];
    }

    public static function simpleUser(): array
    {
        return array_merge(
            UserTypeGroupSerializer::userType(),
            ['simpleUser']
        );
    }

    public static function user(): array
    {
        return array_merge(
            UserTypeGroupSerializer::userType(),
            StaffGroupSerializer::staff(),
            PatientGroupSerializer::patient(),
            ['user']
        );
    }

    public static function loggedUser(): array
    {
        return array_merge(
            UserTypeGroupSerializer::userType(),
            StaffGroupSerializer::staff(),
            PatientGroupSerializer::patient(),
            ['loggedUser']
        );
    }

    public static function simpleApiUserInfo(): array
    {
        return ['simpleApiUserInfo'];
    }
}