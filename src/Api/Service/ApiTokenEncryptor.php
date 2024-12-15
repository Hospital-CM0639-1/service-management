<?php

namespace App\Api\Service;

use App\Common\Service\Utils\Encrypt\AbstractEncryptor;

class ApiTokenEncryptor extends AbstractEncryptor
{
    public function __construct(
        private readonly string $encryptionKey
    ) {}

    protected function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }
}