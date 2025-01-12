<?php

namespace App\Common\Security\Voter\Password;

use App\Common\Entity\User;
use App\Common\Enum\Staff\StaffRoleEnum;
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


        # admin can change password of everyone, except for admin
        if ($loggedUser->isAdmin() && !$user->isAdmin()) {
            return true;
        }

        # staff user (only secretary) can change password only to the patient
        if ($loggedUser->isStaff() && $loggedUser->getStaff()?->getRole() === StaffRoleEnum::SECRETARY && $user->isPatient()) {
            return true;
        }

        return false;
    }
}