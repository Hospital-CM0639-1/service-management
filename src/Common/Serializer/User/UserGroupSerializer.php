<?php

namespace App\Common\Serializer\User;

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
            ['user']
        );
    }

    public static function loggedUser(): array
    {
        return array_merge(
            UserTypeGroupSerializer::userType(),
            ['loggedUser']
        );
    }
}