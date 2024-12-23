<?php

namespace App\Common\Serializer\User;

use App\Common\Serializer\Staff\StaffGroupSerializer;
use App\Common\Serializer\UserType\UserTypeGroupSerializer;

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
            ['user']
        );
    }

    public static function loggedUser(): array
    {
        return array_merge(
            UserTypeGroupSerializer::userType(),
            StaffGroupSerializer::staff(),
            ['loggedUser']
        );
    }

    public static function simpleApiUserInfo(): array
    {
        return ['simpleApiUserInfo'];
    }
}