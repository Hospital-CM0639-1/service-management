<?php

namespace App\Common\Controller\User;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Form\User\UserSearchFilterType;
use App\Common\Model\User\UserSearchFilter;
use App\Common\Serializer\User\UserGroupSerializer;
use App\Common\Service\User\Search\UserSearcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends Controller
{
    public function __construct(
        private readonly UserSearcher $userSearcher
    ) {
    }

    #[Route(path: '/minimal-user', name: 'minimal_users_list', methods: ['GET'])]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
        ]
    )]
    public function usersList(Request $request): Response
    {
        $filter = new UserSearchFilter();
        $form = $this->createForm(UserSearchFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->makeFormErrorJsonResponse(form: $form);
        }

        return $this->makeDataJsonResponse(
            data: $this->userSearcher->search(filter: $filter),
            groups: UserGroupSerializer::minimalUser(),
        );
    }

    #[Route(path: '/user', name: 'simple_users_list', methods: ['GET'])]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
        ]
    )]
    public function simpleUsersList(Request $request): Response
    {
        $filter = new UserSearchFilter();
        $form = $this->createForm(UserSearchFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->makeFormErrorJsonResponse(form: $form);
        }

        return $this->makeDataJsonResponse(
            data: $this->userSearcher->search(filter: $filter),
            groups: UserGroupSerializer::simpleUser(),
        );
    }
}