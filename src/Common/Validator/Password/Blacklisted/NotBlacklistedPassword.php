<?php

namespace App\Common\Validator\Password\Blacklisted;

use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NotBlacklistedPassword extends Constraint
{
    public function __construct(
        public readonly User $user,
        public readonly string $message = CommonErrorCodeEnum::PASSWORD_004,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}