<?php

namespace App\Common\Controller\User;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Security\Voter\User\CanViewUserVoter;
use App\Common\Serializer\Entity\User\UserGroupSerializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DetailController extends Controller
{
    #[Route(
        path: '/user/{userToDetail}',
        name: 'user_detail',
        requirements: ['userToDetail' => '\d+'],
        methods: ['GET']
    )]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
        ]
    )]
    #[IsGranted(
        attribute: CanViewUserVoter::COMMON_CAN_VIEW_USER,
        subject: 'userToDetail',
        message: CommonErrorCodeEnum::DEFAULT_404,
        statusCode: Response::HTTP_NOT_FOUND
    )]
    public function userDetail(User $userToDetail): Response
    {
        return $this->makeDataJsonResponse(
            data: $userToDetail,
            groups: UserGroupSerializer::user(),
        );
    }
}