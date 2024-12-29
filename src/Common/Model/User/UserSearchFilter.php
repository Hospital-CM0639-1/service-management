<?php

namespace App\Common\Model\User;

class UserSearchFilter
{
    private ?bool $status = null;
    private ?string $role = null;
    private ?string $type = null;

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): UserSearchFilter
    {
        $this->status = $status;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): UserSearchFilter
    {
        $this->role = $role;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): UserSearchFilter
    {
        $this->type = $type;
        return $this;
    }
}