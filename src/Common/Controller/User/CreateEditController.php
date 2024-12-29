<?php

namespace App\Common\Controller\User;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\Staff\Staff;
use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Error\FormException;
use App\Common\Error\GenericException;
use App\Common\Form\Staff\StaffType;
use App\Common\Form\User\CreateUserType;
use App\Common\Form\User\EditUserType;
use App\Common\Security\Voter\User\CanViewUserVoter;
use App\Common\Serializer\User\UserGroupSerializer;
use App\Common\Service\User\Action\UserCreateEditManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

class CreateEditController extends Controller
{
    public function __construct(
        private readonly UserCreateEditManager $userCreateEditManager,
    ) {}

    #[Route(
        path: '/user',
        name: 'user_create',
        methods: ['POST']
    )]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
        ]
    )]
    public function userCreate(Request $request): Response
    {
        return $this->manageUser(request: $request, userToManage: new User(), new: true);
    }

    #[Route(
        path: '/user/{userToManage}',
        name: 'user_edit',
        requirements: ['userToManage' => '\d+'],
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
        subject: 'userToManage',
        message: CommonErrorCodeEnum::DEFAULT_404,
        statusCode: Response::HTTP_NOT_FOUND
    )]
    public function userEdit(Request $request, User $userToManage): Response
    {
        if ($this->getUser()->compareTo($userToManage)) {
            return $this->makeEmptyJsonResponse();
        }
        return $this->manageUser(request: $request, userToManage: $userToManage, new: false);
    }

    private function manageUser(Request $request, User $userToManage, bool $new): JsonResponse
    {
        $this->beginTransaction();
        try {

            $new
                ? $this->userCreateEditManager->manageOnCreateUser(request: $request, userToManage: $userToManage)
                : $this->userCreateEditManager->manageOnEditUser(request: $request, userToManage: $userToManage);

            $this->save();
            $this->commit();

        } catch (GenericException $e) {
            $this->rollback();
            return $this->makeErrorMessageJsonResponse(message: $e->getMessage());
        } catch (Throwable $e) {
            $this->rollback();
            throw $e;
        }

        return $this->makeEmptyJsonResponse();
    }
}