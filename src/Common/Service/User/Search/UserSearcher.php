<?php

namespace App\Common\Service\User\Search;

use App\Common\Entity\User;
use App\Common\Model\User\UserSearchFilter;
use App\Common\Repository\User\UserRepository;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use Doctrine\ORM\QueryBuilder;

readonly class UserSearcher
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserHelper $loggedUserHelper,
    ) {}

    /**
     * @param ?UserSearchFilter $filter
     * @param ?User             $searcherUser
     * @return User[]
     */
    public function search(?UserSearchFilter $filter = null, ?User $searcherUser = null): array
    {
        return $this->createBaseQB(filter: $filter, searcherUser: $searcherUser)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param ?UserSearchFilter $filter
     * @param ?User             $searcherUser
     * @return QueryBuilder
     */
    public function createBaseQB(?UserSearchFilter $filter = null, ?User $searcherUser = null): QueryBuilder
    {
        /** @var UserRepository $repo */
        $repo = $this->doctrineHelper->getRepository(User::class);

        /** @var User $userToSearchWith */
        $userToSearchWith = $searcherUser ?: $this->loggedUserHelper->getLoggedUser();

        $qb = $repo->createBaseQBVisibleToUser(user: $userToSearchWith);

        if (!is_null($filter)) {

        }

        return $qb;
    }
}