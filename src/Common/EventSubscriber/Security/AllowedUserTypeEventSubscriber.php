<?php

namespace App\Common\EventSubscriber\Security;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use http\Exception\InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class AllowedUserTypeEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggedUserHelper $loggedUserHelper)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => ['userIsValidType'],
        ];
    }

    public function userIsValidType(ControllerArgumentsEvent $event): void
    {
        $attributes = $event->getAttributes(className: AllowedUserType::class) ?? null;
        if (empty($attributes)) {
            return;
        }

        /** @var AllowedUserType $attribute */
        $attribute = $attributes[0];

        $invalidUserTypes = array_diff($attribute->allowedUserTypes, UserTypeCodeEnum::getAllValues());
        if (!empty($invalidUserTypes)) {
            throw new \Exception('Invalid user types provided: ' . implode(', ', $invalidUserTypes));
        }

        if (!in_array($this->loggedUserHelper->getLoggedUser()->getType()->getCode(), $attribute->allowedUserTypes, false)) {
            throw new AccessDeniedHttpException(CommonErrorCodeEnum::DEFAULT_403);
        }
    }
}