<?php

namespace App\Common\Entity\UserType;

use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'user_types')]
#[ORM\UniqueConstraint(name: 'uq_code', fields: ['code'])]
class UserType
{
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[Groups(['userType'])]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    #[Groups(['userType'])]
    private string $code;

    public function isApi(): bool
    {
        return in_array($this->getCode(), UserTypeCodeEnum::getApiValues(), false);
    }

    /**
     * Verifico se utente staff
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        return UserTypeCodeEnum::STAFF === $this->getCode();
    }

    /**
     * Verifico se utente paziente
     *
     * @return bool
     */
    public function isPatient(): bool
    {
        return UserTypeCodeEnum::PATIENT === $this->getCode();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): UserType
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): UserType
    {
        $this->code = $code;
        return $this;
    }
}