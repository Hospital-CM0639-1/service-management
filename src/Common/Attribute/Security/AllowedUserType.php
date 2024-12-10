<?php

namespace App\Common\Attribute\Security;

#[\Attribute(flags: \Attribute::TARGET_METHOD)]
class AllowedUserType
{
    public function __construct(
        public array $allowedUserTypes
    ) {}
}