<?php

namespace App\Common\EventSubscriber\Auth;

use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Serializer\Entity\User\UserGroupSerializer;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\ResponseHelper;
use App\Common\Service\Utils\Helper\SerializeHelper;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

//use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

readonly class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private DoctrineHelper           $doctrineHelper,
        private SerializeHelper          $serializeHelper,
        private ResponseHelper           $responseHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => ['onAuthenticationSuccess'],
            Events::AUTHENTICATION_FAILURE => ['onAuthenticationFailure'],
            Events::JWT_INVALID => ['onJWTInvalid'],
            Events::JWT_NOT_FOUND => ['onJWTNotFound'],
            Events::JWT_EXPIRED => ['onJWTExpired'],
        ];
    }

//    public function onJWTCreated(JWTCreatedEvent $event): void
//    {
//    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /** @var User $loggedUser */
        $loggedUser = $event->getUser();

        # imposto la data di primo login
        if (is_null($loggedUser->getFirstLoginAt())) {
            $loggedUser->setFirstLoginAt(new DateTime());
        }

        $token = $this->jwtManager->createFromPayload(
            user: $loggedUser,
            payload: [
                'role' => $loggedUser->getStaff()?->getRole(),
                'type' => $loggedUser->getType()->getCode(),
                'id' => $loggedUser->getId(),
            ]
        );

        $loggedUser
            ->setLastToken($token)
            ->setLastLoginAt(new DateTime());
        $this->doctrineHelper->save();

        $event->setData(
            json_decode(
                json: $this->serializeHelper->serialize(
                    data: $loggedUser,
                    groups: UserGroupSerializer::loggedUser()
                ),
                associative: true
            )
        );
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $event->setResponse($this->responseHelper->makeErrorMessageJsonResponse(message: CommonErrorCodeEnum::DEFAULT_002, statusCode: Response::HTTP_UNAUTHORIZED));
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $event->setResponse($this->responseHelper->makeErrorMessageJsonResponse(message: CommonErrorCodeEnum::DEFAULT_401, statusCode: Response::HTTP_UNAUTHORIZED));
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $event->setResponse($this->responseHelper->makeErrorMessageJsonResponse(message: CommonErrorCodeEnum::DEFAULT_401, statusCode: Response::HTTP_UNAUTHORIZED));
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $event->setResponse($this->responseHelper->makeErrorMessageJsonResponse(message: CommonErrorCodeEnum::DEFAULT_401, statusCode: Response::HTTP_UNAUTHORIZED));
    }
}