<?php

namespace App\Common\Entity\Patient;

use App\Common\Enum\CommonEnum;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'patients')]
#[ORM\Index(name: 'idx_patient_name', fields: ['firstName', 'lastName'])]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'patient_id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $lastName;

    #[ORM\Column(type: Types::DATE_MUTABLE, options: ['default' => 'CURRENT_DATE'])]
    #[Groups(['patient'])]
    private DateTime $dateOfBirth;

    #[ORM\Column(type: Types::STRING, length: 50)]
    #[Groups(['patient'])]
    private string $gender;

    #[ORM\Column(type: Types::STRING, length: 20)]
    #[Groups(['patient'])]
    private string $contactNumber;

    #[ORM\Column(type: Types::STRING, length: 200)]
    #[Groups(['patient'])]
    private string $emergencyContactName;

    #[ORM\Column(type: Types::STRING, length: 20)]
    #[Groups(['patient'])]
    private string $emergencyContactNumber;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['patient'])]
    private string $address;

    #[ORM\Column(type: Types::STRING)]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Groups(['patient'])]
    private string $insuranceProvider;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Groups(['patient'])]
    private string $insurancePolicyNumber;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $lastUpdated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Patient
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Patient
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTime $dateOfBirth): Patient
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): Patient
    {
        $this->gender = $gender;
        return $this;
    }

    public function getContactNumber(): string
    {
        return $this->contactNumber;
    }

    public function setContactNumber(string $contactNumber): Patient
    {
        $this->contactNumber = $contactNumber;
        return $this;
    }

    public function getEmergencyContactName(): string
    {
        return $this->emergencyContactName;
    }

    public function setEmergencyContactName(string $emergencyContactName): Patient
    {
        $this->emergencyContactName = $emergencyContactName;
        return $this;
    }

    public function getEmergencyContactNumber(): string
    {
        return $this->emergencyContactNumber;
    }

    public function setEmergencyContactNumber(string $emergencyContactNumber): Patient
    {
        $this->emergencyContactNumber = $emergencyContactNumber;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): Patient
    {
        $this->address = $address;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Patient
    {
        $this->email = $email;
        return $this;
    }

    public function getInsuranceProvider(): string
    {
        return $this->insuranceProvider;
    }

    public function setInsuranceProvider(string $insuranceProvider): Patient
    {
        $this->insuranceProvider = $insuranceProvider;
        return $this;
    }

    public function getInsurancePolicyNumber(): string
    {
        return $this->insurancePolicyNumber;
    }

    public function setInsurancePolicyNumber(string $insurancePolicyNumber): Patient
    {
        $this->insurancePolicyNumber = $insurancePolicyNumber;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Patient
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getLastUpdated(): DateTime
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(DateTime $lastUpdated): Patient
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }
}