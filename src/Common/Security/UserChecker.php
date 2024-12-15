<?php

namespace App\Common\Security;

use App\Common\Entity\User;
use App\Common\Service\Utils\Helper\JwtHelper;
use App\Common\Service\Utils\Helper\RequestHelper;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private JwtHelper $jwtHelper,
        private RequestHelper $requestHelper,
    ) {
    }

    public function checkPreAuth(UserInterface|User $user): void
    {
        if ($user->isApi()) {
            throw new DisabledException();
        }
    }

    public function checkPostAuth(UserInterface|User $user): void
    {
        if (!$this->requestHelper->isPublicAccessPath() && $user->getLastToken() !== $this->jwtHelper->getRequestToken()) {
            throw new ExpiredTokenException();
        }
    }
}