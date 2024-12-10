<?php

namespace App\Common\Service\Utils\Helper;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Throwable;

readonly class JwtHelper
{
    public function __construct(
        private RequestHelper $requestHelper,
        private JWTTokenManagerInterface $tokenManager
    ) {}

    public function getRequestToken(): ?string
    {
        $authHeader = $this->requestHelper->getRequest()->headers->get('Authorization', null);
        if (empty($authHeader)) {
            return null;
        }

        if (str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7); # rimuovo il bearer
        } else {
            $token = $authHeader;
        }

        try {
            $this->tokenManager->parse(token: $token);
        } catch (Throwable) {
            return null;
        }

        return $token;
    }
}