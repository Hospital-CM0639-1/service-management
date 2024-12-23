<?php

namespace App\Common\Regex\User;

final class UserRegex
{
    public const USERNAME = '/^[a-zA-Z0-9\-\.]{3,255}$/';
}