<?php

namespace App\Common\Entity\Password;

use App\Common\Entity\User;
use App\Common\Enum\CommonEnum;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'user_password_histories')]
class UserPasswordHistory
{
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[Groups(['minimalUser'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column]
    private string $password;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Gedmo\Blameable(on: 'create')]
    private ?User $createdBy;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserPasswordHistory
    {
        $this->user = $user;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserPasswordHistory
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): UserPasswordHistory
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): UserPasswordHistory
    {
        $this->createdBy = $createdBy;
        return $this;
    }

}