<?php

namespace App\Common\Entity\Log\User;

use App\Common\Entity\User;
use App\Common\Enum\CommonEnum;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table(name: 'enable_disable_user_logs')]
#[ORM\Entity]
class EnableDisableUserLog
{
    public const ACTION_DISABLE = 'disable';
    public const ACTION_ENABLE = 'enable';

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Gedmo\Blameable(on: 'create')]
    private ?User $createdBy = null;

    #[ORM\Column]
    private string $action;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): EnableDisableUserLog
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): EnableDisableUserLog
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): EnableDisableUserLog
    {
        $this->action = $action;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): EnableDisableUserLog
    {
        $this->user = $user;
        return $this;
    }
}