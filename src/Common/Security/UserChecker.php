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
        if ($user->isApi() || !$user->isActive()) {
            throw new DisabledException();
        }
    }

    public function checkPostAuth(UserInterface|User $user): void
    {
        # if api user, no particular checks
        if ($user->isApi()) {
            return;
        }

        if (!$user->isActive()) {
            throw new DisabledException();
        }

        $this->verifyTokenValidity(user: $user);
        $this->verifyPasswordValidity(user: $user);
    }

    /**
     * Verify if we are not in a public path and the header token is equals to the last one of the user
     *
     * @param User $user
     * @return void
     */
    private function verifyTokenValidity(User $user): void
    {
        if (
            !$this->requestHelper->isPublicAccessPath()
            && $user->getLastToken() !== $this->jwtHelper->getRequestToken()
        ) {
            throw new ExpiredTokenException();
        }
    }

    /**
     * Verify if we are not in a public path and not in the change password one and the password of user is expired
     *
     * @param User $user
     * @return void
     */
    private function verifyPasswordValidity(User $user): void
    {
        if (
            !$this->requestHelper->isPublicAccessPath()
            && !$this->requestHelper->isChangePasswordPath()
            && $user->isPasswordExpired()
        ) {
            throw new ExpiredTokenException();
        }
    }
}