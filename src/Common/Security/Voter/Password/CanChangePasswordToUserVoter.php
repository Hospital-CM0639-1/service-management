<?php

namespace App\Common\Security\Voter\Password;

use App\Common\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanChangePasswordToUserVoter extends Voter
{
    public const CAN_CHANGE_PASSWORD_TO_USER = 'CAN_CHANGE_PASSWORD_TO_USER';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::CAN_CHANGE_PASSWORD_TO_USER;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $subject;

        /** @var User $loggedUser */
        $loggedUser = $token->getUser();

        # the logged user must not the user whose password is changing
        # and it must be a staff user
        return !$user->compareTo($loggedUser)
            && $user->isStaff();
    }
}