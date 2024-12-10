<?php

namespace App\Common\Model\User;

class UserSearchFilter
{
    private ?bool $active = null;

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): UserSearchFilter
    {
        $this->active = $active;
        return $this;
    }
}