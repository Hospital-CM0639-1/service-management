<?php

namespace App\Common\Controller\User;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\Log\User\EnableDisableUserLog;
use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Security\Voter\User\CanViewUserVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EnableDisableController extends Controller
{
    #[Route(
        path: '/user/{targetUser}/{enableDisable}',
        name: 'enable_disable_user',
        requirements: ['targetUser' => '\d+', 'enableDisable' => 'enable|disable'],
        methods: ['PUT']
    )]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
        ]
    )]
    #[IsGranted(
        attribute: CanViewUserVoter::COMMON_CAN_VIEW_USER,
        subject: 'targetUser',
        message: CommonErrorCodeEnum::DEFAULT_404,
        statusCode: Response::HTTP_NOT_FOUND
    )]
    public function enableDisableUser(User $targetUser, string $enableDisable): JsonResponse
    {
        if ($this->getUser()->compareTo($targetUser)) {
            return $this->makeEmptyJsonResponse();
        }
        $wasActive = $targetUser->isActive();

        $this->beginTransaction();
        try {
            if ($wasActive && $enableDisable === 'disable') { # deactivation
                $targetUser
                    ->setLastToken(null) # force logout
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