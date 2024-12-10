<?php

namespace App\Common\Security\Voter\User;

use App\Common\Entity\User;
use App\Common\Service\User\UserChecker;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanViewUserVoter extends Voter
{
    public const COMMON_CAN_VIEW_USER = 'COMMON_CAN_VIEW_USER';

    public function __construct(
        private readonly UserChecker $userChecker
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::COMMON_CAN_VIEW_USER;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $subject;

        return $this->userChecker->userCanViewUsers(users: $user);
    }
}