<?php

namespace App\Api\EventSubscriber;

use App\Api\Attribute\ContainsApiToken;
use App\Api\Entity\ApiToken;
use App\Api\Service\ApiTokenEncryptor;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\RequestHelper;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class ContainsApiTokenEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ApiTokenEncryptor $apiTokenEncryptor,
        private DoctrineHelper    $doctrineHelper,
        private RequestHelper     $requestHelper,
        private string            $apiTokenHeaderFieldName,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => ['containsApiToken'],
        ];
    }

    public function containsApiToken(ControllerArgumentsEvent $event): void
    {
        $attributes = $event->getAttributes(className: ContainsApiToken::class) ?? null;
        if (empty($attributes)) {
            return;
        }

        $apiToken = $this->requestHelper->getHeaderByKey(key: $this->apiTokenHeaderFieldName);
        if (empty($apiToken)) {
            throw new AccessDeniedHttpException(CommonErrorCodeEnum::DEFAULT_403);
        }

        $tokenPayload = explode('_', $apiToken);
        if (count($tokenPayload) !== 3) {
            throw new AccessDeniedHttpException(CommonErrorCodeEnum::DEFAULT_403);
        }

        $token = $tokenPayload[2];
        $apiTokens = $this->doctrineHelper->getRepository(ApiToken::class)->findBy([
            'user' => (int) $tokenPayload[0],
        ]);

        $validApiToken = null;
        $todayYmd = (new DateTime())->format('Y-m-d');
        foreach ($apiTokens as $apiToken) {
            $validFrom = $apiToken->getValidFrom();
            $validTo = $apiToken->getValidTo();
            dump($this->apiTokenEncryptor->decrypt($apiToken->getToken()));
            $valid = $this->apiTokenEncryptor->decrypt($apiToken->getToken()) === $token
                && (is_null($validFrom) || $validFrom->format('Y-m-d') <= $todayYmd)
                && (is_null($validTo) || $validTo->format('Y-m-d') >= $todayYmd);
            if ($valid) {
                $validApiToken = $apiToken;
            }
        }

        if (is_null($validApiToken)) {
            throw new AccessDeniedHttpException(CommonErrorCodeEnum::DEFAULT_403);
        }

    }
}