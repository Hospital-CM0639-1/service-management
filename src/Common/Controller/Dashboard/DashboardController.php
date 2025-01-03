<?php

namespace App\Common\Controller\Dashboard;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Serializer\Service\Dashboard\Admin\AdminDashboardGroupsHelper;
use App\Common\Service\Dashboard\Admin\AdminDashboardMaker;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardMaker $adminDashboardMaker,
    ) {}

    #[Route(path: '/admin/dashboard', methods: ['GET'])]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
        ]
    )]
    public function adminDashboard(): Response
    {
        return $this->makeDataJsonResponse(
            data: $this->adminDashboardMaker->makeDashboard(),
            groups: AdminDashboardGroupsHelper::adminDashboard(),
        );
    }
}