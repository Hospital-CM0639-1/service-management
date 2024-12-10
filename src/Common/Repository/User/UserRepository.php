<?php

namespace App\Common\Repository\User;

use App\Common\Entity\User;
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
        return match ($user->getType()->getCode()) {
            UserTypeCodeEnum::ADMIN => $this->createBaseQBVisibleToAdmin(user: $user, alias: $alias),
        };
    }

    private function createBaseQBVisibleToAdmin(User $user, string $alias = 'u'): QueryBuilder
    {
        return $this->createBaseQB(alias: $alias);
    }
}