<?php

namespace App\Common\Entity\Staff;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'staff')]
#[ORM\Index(name: 'idx_staff_role', columns: ['role'])]
class Staff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'staff_id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $lastName;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    #[Groups(['staff'])]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['staff'])]
    private string $role;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    #[Groups(['staff'])]
    private ?string $department = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    #[Groups(['staff'])]
    private ?string $specialization = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, options: ['default' => 'CURRENT_DATE'])]
    #[Groups(['staff'])]
    private DateTime $hireDate;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;
        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(?string $specialization): self
    {
        $this->specialization = $specialization;
        return $this;
    }

    public function getHireDate(): DateTime
    {
        return $this->hireDate;
    }

    public function setHireDate(DateTime $hireDate): Staff
    {
        $this->hireDate = $hireDate;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}