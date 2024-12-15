<?php

namespace App\Api\Entity;

use App\Common\Entity\User;
use App\Common\Enum\CommonEnum;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'api_tokens')]
#[ORM\Index(name: 'idx_token', fields: ['token'])]
#[ORM\Index(name: 'idx_valid_from', fields: ['validFrom'])]
#[ORM\Index(name: 'idx_valid_to', fields: ['validTo'])]
class ApiToken
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $validFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $validTo = null;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(length: 512)]
    private string $token;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): ApiToken
    {
        $this->user = $user;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): ApiToken
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getValidFrom(): ?DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(?DateTime $validFrom): ApiToken
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getValidTo(): ?DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(?DateTime $validTo): ApiToken
    {
        $this->validTo = $validTo;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ApiToken
    {
        $this->name = $name;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): ApiToken
    {
        $this->token = $token;
        return $this;
    }
}