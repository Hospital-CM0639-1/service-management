<?php

namespace App\Common\Regex\Password;

final class PasswordRegex
{
    public const USER_PASSWORD = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
}