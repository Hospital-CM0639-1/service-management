<?php

namespace App\Common\Controller\User;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\Log\User\EnableDisableUserLog;
use App\Common\Entity\User;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class EnableDisableController extends Controller
{
    #[Route(
        path: '/user/{targetUser}/{enableDisable}',
        name: 'enable_disable_user',
        requirements: ['targetUser' => '\d+', 'enableDisable' => 'enable|disable'],
        methods: ['PUT']
    )]
    #[AllowedUserType(allowedUserTypes: [UserTypeCodeEnum::ADMIN])]
    public function enableDisableUser(User $targetUser, string $enableDisable): JsonResponse
    {
        $wasActive = $targetUser->isActive();

        $this->beginTransaction();
        try {
            if ($wasActive && $enableDisable === 'disable') { # deactivation
                $targetUser
                    ->setActive(false)
                    ->getStaff()?->setIsActive(false);
                $log = (new EnableDisableUserLog())
                    ->setUser($targetUser)
                    ->setAction(EnableDisableUserLog::ACTION_DISABLE);
                $this->persist($log);
            } elseif (!$wasActive && $enableDisable === 'enable') { # activation
                $targetUser
                    ->setActive(true)
                    ->getStaff()?->setIsActive(true);
                $log = (new EnableDisableUserLog())
                    ->setUser($targetUser)
                    ->setAction(EnableDisableUserLog::ACTION_ENABLE);
                $this->persist($log);
            }

            $this->save();
            $this->commit();

        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }

        return $this->makeEmptyJsonResponse();
    }
}