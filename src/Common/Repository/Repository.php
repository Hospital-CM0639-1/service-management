<?php

namespace App\Common\Repository;

use App\Common\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class Repository extends EntityRepository
{
    /**
     * QB di base
     *
     * @param string $alias
     * @return QueryBuilder
     */
    abstract public function createBaseQB(string $alias): QueryBuilder;

    /**
     * QB di base con cui vengono già applicati dei filtri in bae all'utente loggato
     *
     * @param User $user
     * @param string $alias
     * @return QueryBuilder
     */
    abstract public function createBaseQBVisibleToUser(User $user, string $alias): QueryBuilder;
}