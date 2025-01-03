<?php

namespace App\Common\Model\Form\Password;

class ChangePassword
{
    private ?string $oldPassword = null;
    private string $newPassword;
    private string $repeatedPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): ChangePassword
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): ChangePassword
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    public function getRepeatedPassword(): string
    {
        return $this->repeatedPassword;
    }

    public function setRepeatedPassword(string $repeatedPassword): ChangePassword
    {
        $this->repeatedPassword = $repeatedPassword;
        return $this;
    }
}