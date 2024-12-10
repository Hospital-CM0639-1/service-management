<?php

namespace App\Common\Service\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\AccessMapInterface;

readonly class RequestHelper
{
    public function __construct(
        private RequestStack $requestStack,
        private AccessMapInterface $accessMap,
    ) {}

    public function getRequest(): Request
    {
        return $this->requestStack->getMainRequest();
    }

    public function isPublicAccessPath(): bool
    {
        return in_array('PUBLIC_ACCESS', $this->accessMap->getPatterns(request: $this->getRequest())[0] ?? [], false);
    }
}