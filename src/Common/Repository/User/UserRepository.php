<?php

namespace App\Common\Repository\User;

use App\Common\Entity\User;
use App\Common\Entity\UserType\UserType;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Repository\Repository;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends Repository
{
    public function createBaseQB(string $alias = 'u'): QueryBuilder
    {
        return $this
            ->createQueryBuilder(alias: $alias);
    }

    /**
     * Base qb to get all users visible to the given one
     *
     * @param User   $user
     * @param string $alias
     * @return QueryBuilder
     * @throws \Exception
     */
    public function createBaseQBVisibleToUser(User $user, string $alias = 'u'): QueryBuilder
    {
        # only admin and staff user can see user list
        if (!in_array($user->getType()->getCode(), [UserTypeCodeEnum::ADMIN, UserTypeCodeEnum::STAFF], true)) {
            throw new \Exception('User type not supported');
        }

        $qb = $this->createBaseQB(alias: $alias);

        $inUserTypesQB = $this->getEntityManager()->createQueryBuilder()
            ->select('z_ut.id')
            ->from(UserType::class, 'z_ut')
            ->andWhere('z_ut.code in (:visibleUserTypeCodes)');

        return $qb
            ->andWhere(
                $this->getEntityManager()->getExpressionBuilder()->in(
                    'u.type',
                    $inUserTypesQB->getDQL(),
                )
            )
            ->setParameter(
                'visibleUserTypeCodes',
                UserTypeCodeEnum::getTypesVisibleToType(userType: $user->getType()->getCode())
            );
    }
}