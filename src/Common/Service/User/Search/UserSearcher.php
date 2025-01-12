<?php

namespace App\Common\Service\User\Search;

use App\Common\Entity\Staff\Staff;
use App\Common\Entity\User;
use App\Common\Entity\UserType\UserType;
use App\Common\Model\Form\User\UserSearchFilter;
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
            $active = $filter->getStatus();
            if (!is_null($active)) {
                $qb
                    ->andWhere('u.active = :active')
                    ->setParameter('active', $active === true ? 1 : 0);
            }

            $role = $filter->getRole();
            if (!is_null($role)) {

                # i look for the staffRow associated to the user
                # with role equals to the given one
                $existStaffRowWithRoleQb = $this->doctrineHelper->createORMQueryBuilder()
                    ->select('1')
                    ->from(Staff::class, 'zz_s')
                    ->andWhere('zz_s.role = :role')
                    ->andWhere('zz_s = u.staff');

                $qb
                    ->andWhere($this->doctrineHelper->getExpressionBuilder()->exists($existStaffRowWithRoleQb->getDQL()))
                    ->setParameter('role', $role);
            }

            $type = $filter->getType();
            if (!is_null($type)) {

                $userType = $this->doctrineHelper->getRepository(UserType::class)->findOneBy(['code' => $type]);

                # user type is equal to the given one
                $qb
                    ->andWhere('u.type = :userType')
                    ->setParameter('userType', $userType);
            }
        }

        return $qb;
    }
}