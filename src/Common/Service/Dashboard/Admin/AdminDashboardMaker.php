<?php

namespace App\Common\Service\Dashboard\Admin;

use App\Common\Model\Service\Dashboard\Admin\AdminDashboard;

readonly class AdminDashboardMaker
{
    public function __construct(
        private AdminDashboardQueryService $queryService
    ) {}

    /**
     * Generate admin dashboard with information about user
     *
     * @return AdminDashboard
     */
    public function makeDashboard(): AdminDashboard
    {
        $result = $this->queryService->getDashboardData();

        return new AdminDashboard(
            admin: (int) $result['admin'],
            doctors: (int) $result['doctors'],
            nurses: (int) $result['nurses'],
            secretaries: (int) $result['secretaries'],
            patients: (int) $result['patients']
        );
    }
}