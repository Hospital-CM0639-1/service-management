<?php

namespace App\Api\Model\TokenValidation;

use App\Common\Entity\User;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class TokenValidationResult
{
    #[Groups(['apiGateway', 'service'])]
    private bool $valid;

    #[Groups(['service'])]
    private ?User $user;

    #[Groups(['apiGateway', 'service'])]
    private ?string $invalidReason;

    public function __construct(bool $valid, ?User $user = null, ?string $invalidReason = null)
    {
        $this->valid = $valid;
        $this->user = $user;
        $this->invalidReason = $invalidReason;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getInvalidReason(): ?string
    {
        return $this->invalidReason;
    }

}