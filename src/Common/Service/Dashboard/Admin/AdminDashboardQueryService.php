<?php

namespace App\Common\Service\Dashboard\Admin;

use App\Common\Entity\User;
use App\Common\Enum\Staff\StaffRoleEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Repository\User\UserRepository;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use Doctrine\ORM\QueryBuilder;

readonly class AdminDashboardQueryService
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserHelper $loggedUserHelper,
    ) {}

    /**
     * Get data to create dashboard for admin user, which contains the number of:
     * - admin;
     * - doctors;
     * - nurses;
     * - secretaries;
     * - patients;
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        return [
            'admin' => $this->countAdminUsers(),
            'doctors' => $this->countDoctorUsers(),
            'nurses' => $this->countNurseUsers(),
            'secretaries' => $this->countSecretaryUsers(),
            'patients' => $this->countPatientUsers(),
        ];
    }

    /**
     * Get number of admin users
     *
     * @return int
     */
    private function countAdminUsers(): int
    {
        return $this
            ->createBaseQB(type: UserTypeCodeEnum::ADMIN)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get number of doctor users
     *
     * @return int
     */
    private function countDoctorUsers(): int
    {
        return $this
            ->createStaffUserBaseQB(role: StaffRoleEnum::DOCTOR)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get number of nurse users
     *
     * @return int
     */
    private function countNurseUsers(): int
    {
        return $this
            ->createStaffUserBaseQB(role: StaffRoleEnum::NURSE)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get number of secretary users
     *
     * @return int
     */
    private function countSecretaryUsers(): int
    {
        return $this
            ->createStaffUserBaseQB(role: StaffRoleEnum::SECRETARY)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get number of patient users
     *
     * @return int
     */
    private function countPatientUsers(): int
    {
        return $this
            ->createBaseQB(type: UserTypeCodeEnum::PATIENT)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Base QB for query to get data about staff users
     *
     * @param string $role
     * @return QueryBuilder
     */
    private function createStaffUserBaseQB(string $role): QueryBuilder
    {
        return $this
            ->createBaseQB(type: UserTypeCodeEnum::STAFF)
            ->join('u.staff', 's')
            ->andWhere('s.role = :role')
            ->setParameter('role', $role);
    }

    /**
     * Base QB for query to generate dashboard
     *
     * @param string $type
     * @return QueryBuilder
     */
    private function createBaseQB(string $type): QueryBuilder
    {
        /** @var UserRepository $repo */
        $repo = $this->doctrineHelper->getRepository(User::class);

        return $repo
            ->createBaseQBVisibleToUser(user: $this->loggedUserHelper->getLoggedUser())
            ->select(['count(u.id)'])
            ->join('u.type', 'ut')
            ->andWhere('ut.code = :type')
            ->setParameter('type', $type);
    }
}