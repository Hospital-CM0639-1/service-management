<?php

namespace App\Api\Serializer\TokenValidation;

use App\Common\Serializer\Entity\User\UserGroupSerializer;

class TokenValidationResultGroupSerializer
{
    public static function service(): array
    {
        return array_merge(
            UserGroupSerializer::simpleApiUserInfo(),
            ['service']
        );
    }

    public static function apiGateway(): array
    {
        return ['apiGateway'];
    }
}