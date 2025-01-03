<?php

namespace App\Common\Model\Service\Dashboard\Admin;

use Symfony\Component\Serializer\Attribute\Groups;

readonly class AdminDashboard
{
    #[Groups(['adminDashboard'])]
    private int $admin;

    #[Groups(['adminDashboard'])]
    private int $doctors;

    #[Groups(['adminDashboard'])]
    private int $nurses;

    #[Groups(['adminDashboard'])]
    private int $secretaries;

    #[Groups(['adminDashboard'])]
    private int $patients;

    public function __construct(int $admin, int $doctors, int $nurses, int $secretaries, int $patients)
    {
        $this->admin = $admin;
        $this->doctors = $doctors;
        $this->nurses = $nurses;
        $this->secretaries = $secretaries;
        $this->patients = $patients;
    }

    public function getAdmin(): int
    {
        return $this->admin;
    }

    public function getDoctors(): int
    {
        return $this->doctors;
    }

    public function getNurses(): int
    {
        return $this->nurses;
    }

    public function getSecretaries(): int
    {
        return $this->secretaries;
    }

    public function getPatients(): int
    {
        return $this->patients;
    }
}