<?php

namespace App\Common\Controller;

use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use App\Common\Service\Utils\Helper\SerializeHelper;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;

class Controller extends AbstractController
{
    protected readonly DoctrineHelper $doctrineHelper;
    protected readonly SerializeHelper $serializeHelper;
    protected readonly LoggedUserHelper $loggedUserHelper;

    #[Required]
    public function setDoctrineHelper(DoctrineHelper $doctrineHelper): void
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    #[Required]
    public function setSerializeHelper(SerializeHelper $serializeHelper): void
    {
        $this->serializeHelper = $serializeHelper;
    }

    #[Required]
    public function setLoggedUserHelper(LoggedUserHelper $loggedUserHelper): Controller
    {
        $this->loggedUserHelper = $loggedUserHelper;
        return $this;
    }

    #### ================================================
    #### ==== LOGGED USER
    #### ================================================

    public function getUser(): null|User|UserInterface
    {
        return $this->loggedUserHelper->getLoggedUser();
    }

    #### ================================================
    #### ==== DOCTRINE HELPER
    #### ================================================

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->doctrineHelper->getEntityManager();
    }

    public function getConnection(): Connection
    {
        return $this->doctrineHelper->getConnection();
    }

    public function getRepository(string $entityClass): EntityRepository
    {
        return $this->doctrineHelper->getRepository($entityClass);
    }

    public function flush(): void
    {
        $this->doctrineHelper->flush();
    }

    public function persist(object|array $entity): void
    {
        $this->doctrineHelper->persist($entity);
    }

    public function remove(object|array $entity): void
    {
        $this->doctrineHelper->remove($entity);
    }

    public function save(object|array $entity = []): void
    {
        $this->doctrineHelper->save($entity);
    }

    public function beginTransaction(): void
    {
        $this->doctrineHelper->beginTransaction();
    }

    public function rollback(): void
    {
        $this->doctrineHelper->rollback();
    }

    public function commit(): void
    {
        $this->doctrineHelper->commit();
    }

    public function createORMQueryBuilder(): ORMQueryBuilder
    {
        return $this->doctrineHelper->createORMQueryBuilder();
    }

    public function createDBALQueryBuilder(): DBALQueryBuilder
    {
        return $this->doctrineHelper->createDBALQueryBuilder();
    }

    #### ================================================
    #### ==== SERIALIZER
    #### ================================================

    public function makeDataJsonResponse(mixed $data, array $groups = [], int $statusCode = Response::HTTP_OK, string $format = 'json'): JsonResponse
    {
        return JsonResponse::fromJsonString(
            data: $this->serializeHelper->serialize(
                data: $data,
                groups: $groups,
                format: $format
            ),
            status: $statusCode
        );
    }

    public function makeFormErrorJsonResponse(FormInterface $form, int $statusCode = Response::HTTP_BAD_REQUEST, string $format = 'json'): JsonResponse
    {
        return JsonResponse::fromJsonString(
            data: $this->serializeHelper->serialize(
                data: ['error' => $form->getErrors(true)->current()->getMessage()],
                format: $format
            ),
            status: $statusCode
        );
    }

    public function makeErrorMessageJsonResponse(string $message = CommonErrorCodeEnum::DEFAULT_400, int $statusCode = Response::HTTP_BAD_REQUEST, string $format = 'json'): JsonResponse
    {
        return JsonResponse::fromJsonString(
            data: $this->serializeHelper->serialize(
                data: ['error' => $message],
                format: $format
            ),
            status: $statusCode
        );
    }

    public function makeEmptyJsonResponse(int $statusCode = Response::HTTP_OK, string $format = 'json'): JsonResponse
    {
        return $this->makeDataJsonResponse(
            data: [],
            statusCode: $statusCode,
            format: $format
        );
    }
}