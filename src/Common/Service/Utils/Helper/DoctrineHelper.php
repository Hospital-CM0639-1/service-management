<?php

namespace App\Common\Service\Utils\Helper;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;

readonly class DoctrineHelper
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }

    public function getRepository(string $entityClass): EntityRepository
    {
        return $this->getEntityManager()->getRepository($entityClass);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function persist(object|array $entity): void
    {
        $entities = is_array($entity) ? $entity : [$entity];
        $entityManager = $this->getEntityManager();
        foreach ($entities as $entity) {
            $entityManager->persist($entity);
        }
    }

    public function remove(object|array $entity): void
    {
        $entities = is_array($entity) ? $entity : [$entity];
        $entityManager = $this->getEntityManager();
        foreach ($entities as $entity) {
            $entityManager->remove($entity);
        }

        $this->flush();
    }

    public function save(object|array $entity = []): void
    {
        $this->persist($entity);
        $this->flush();
    }

    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }

    public function getExpressionBuilder(): Expr
    {
        return $this->getEntityManager()->getExpressionBuilder();
    }

    public function createORMQueryBuilder(): ORMQueryBuilder
    {
        return $this->getEntityManager()->createQueryBuilder();
    }

    public function createDBALQueryBuilder(): DBALQueryBuilder
    {
        return $this->getConnection()->createQueryBuilder();
    }

    public function executeQuery(string $sql, array $params = [], $types = [], ?QueryCacheProfile $qcp = null): ?Result
    {
        return $this->getConnection()->executeQuery(sql: $sql, params: $params, types: $types, qcp: $qcp);
    }
}