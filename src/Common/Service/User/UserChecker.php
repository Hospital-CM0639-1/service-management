<?php

namespace App\Common\Service\User;

use App\Common\Entity\User;
use App\Common\Repository\User\UserRepository;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\LoggedUserHelper;

readonly class UserChecker
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserHelper $loggedUserHelper,
    ) {}

    public function userCanViewUsers(User|array $users, ?User $user = null): bool
    {
        /** @var UserRepository $repo */
        $repo = $this->doctrineHelper->getRepository(User::class);

        $users = is_array($users) ? $users : [$users];
        if (empty($users)) {
            return false;
        }

        $userToFilterWith = $user ?: $this->loggedUserHelper->getLoggedUser();

        $providedUsers = $repo
            ->createBaseQBVisibleToUser(user: $userToFilterWith)
            ->select('count(u)')
            ->andWhere('u in (:users)')
            ->setParameter('users', $users)
            ->getQuery()
            ->getSingleScalarResult();

        return count($users) === (int) $providedUsers;
    }
}