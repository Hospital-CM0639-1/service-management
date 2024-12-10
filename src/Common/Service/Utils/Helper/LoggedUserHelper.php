<?php

namespace App\Common\Service\Utils\Helper;

use App\Common\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class LoggedUserHelper
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {}

    public function getLoggedUser(): null|User|UserInterface
    {
        return $this->tokenStorage->getToken()?->getUser();
    }
}