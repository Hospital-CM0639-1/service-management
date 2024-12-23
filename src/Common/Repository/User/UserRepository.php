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

    public function createBaseQBVisibleToUser(User $user, string $alias = 'u'): QueryBuilder
    {
        $qb = match ($user->getType()->getCode()) {
            UserTypeCodeEnum::ADMIN => $this->createBaseQBVisibleToAdmin(user: $user, alias: $alias),
        };

        $notInUserTypeApi = $this->getEntityManager()->createQueryBuilder()
            ->select('z_ut.id')
            ->from(UserType::class, 'z_ut')
            ->andWhere('z_ut.code in (:apiUserTypeCode)');

        $qb
            ->andWhere(
                $this->getEntityManager()->getExpressionBuilder()->notIn(
                    'u.type',
                    $notInUserTypeApi->getDQL(),
                )
            )
            ->setParameter('apiUserTypeCode', UserTypeCodeEnum::getApiValues());

        return $qb;
    }

    private function createBaseQBVisibleToAdmin(User $user, string $alias = 'u'): QueryBuilder
    {
        return $this->createBaseQB(alias: $alias);
    }
}