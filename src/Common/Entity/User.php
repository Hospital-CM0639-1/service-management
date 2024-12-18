<?php

namespace App\Common\Entity;

use App\Common\Entity\Staff\Staff;
use App\Common\Entity\UserType\UserType;
use App\Common\Enum\CommonEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Repository\User\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'uq_username', fields: ['username'])]
#[ORM\Index(name: 'idx_email', fields: ['email'])]
#[ORM\Index(name: 'idx_active', fields: ['active'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const PASSWORD_TTL_IN_DAYS = 90;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[Groups([
        'minimalUser', 'simpleUser', 'user', 'loggedUser',
        'simpleApiUserInfo'
    ])]
    private int $id;

    #[ManyToOne(targetEntity: UserType::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['simpleUser', 'user', 'loggedUser'])]
    private UserType $type;

    #[ORM\Column]
    #[Groups(['minimalUser', 'simpleUser', 'user', 'loggedUser'])]
    #[SerializedName('firstName')]
    private string $name;

    #[ORM\Column]
    #[Groups([
        'minimalUser', 'simpleUser', 'user', 'loggedUser',
        'simpleApiUserInfo'
    ])]
    #[SerializedName('lastName')]
    private string $surname;

    #[ORM\Column]
    #[Groups(['simpleUser', 'user', 'loggedUser'])]
    private string $email;

    #[ORM\Column]
    #[Groups(['simpleUser', 'user', 'loggedUser'])]
    private string $username;

    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Groups(['loggedUser'])]
    private ?DateTime $lastLoginAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?DateTime $firstLoginAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Gedmo\Blameable(on: 'create')]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?DateTime $passwordChangedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?DateTime $passwordRequestAt = null;

    #[ORM\Column(type: Types::TEXT, length: CommonEnum::TEXT_LENGTH, nullable: true)]
    #[Groups(['loggedUser'])]
    #[SerializedName('token')]
    private ?string $lastToken = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $active = true;

    #[ORM\ManyToOne(targetEntity: Staff::class)]
    private ?Staff $staff = null;

    ######## ================================================
    ######## === VIRTUAL PROPERTIES
    ######## ================================================

    #[Groups(['simpleApiUserInfo'])]
    #[SerializedName('type')]
    public function getSimpleApiUserInfoType(): string
    {
        return $this->getType()->getCode();
    }

    #[Groups(['simpleApiUserInfo'])]
    #[SerializedName('role')]
    public function getSimpleApiUserInfoRole(): ?string
    {
        return $this->getStaff()?->getRole();
    }

    #[Groups(['loggedUser'])]
    #[SerializedName('expiredPassword')]
    public function isPasswordExpired(): bool
    {
        $passwordTtlInDays = self::PASSWORD_TTL_IN_DAYS;

        # If password was never changed, it'll be considered as expired
        if (is_null($this->getPasswordChangedAt())) {
            return true;
        }

        $expiryThreshold = (clone $this->getPasswordChangedAt())->modify("+{$passwordTtlInDays} days");

        return new DateTime() > $expiryThreshold;
    }

    ######## ================================================
    ######## === COSTRUTTORE
    ######## ================================================

    public function __construct()
    {
    }

    ######## ================================================
    ######## === ALTRI METODI
    ######## ================================================

    public function getRoles(): array
    {
        return array_merge(['ROLE_USER'], ['ROLE_' . strtoupper($this->getType()->getCode())]);
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return ($_ENV['USER_AUTH_FIELD'] ?? 'username') === 'username' ? $this->getUsername() : $this->getEmail();
    }

    /**
     * Verifico se utente admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return UserTypeCodeEnum::ADMIN === $this->getType()->getCode();
    }

    /**
     * Verifico se utente api
     *
     * @return bool
     */
    public function isApi(): bool
    {
        return UserTypeCodeEnum::API === $this->getType()->getCode();
    }

    ######## ================================================
    ######## === GETTERS & SETTERS
    ######## ================================================

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): UserType
    {
        return $this->type;
    }

    public function setType(UserType $type): User
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): User
    {
        $this->surname = $surname;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getLastLoginAt(): ?DateTime
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?DateTime $lastLoginAt): User
    {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    public function getFirstLoginAt(): ?DateTime
    {
        return $this->firstLoginAt;
    }

    public function setFirstLoginAt(?DateTime $firstLoginAt): User
    {
        $this->firstLoginAt = $firstLoginAt;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): User
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getPasswordChangedAt(): ?DateTime
    {
        return $this->passwordChangedAt;
    }

    public function setPasswordChangedAt(?DateTime $passwordChangedAt): User
    {
        $this->passwordChangedAt = $passwordChangedAt;
        return $this;
    }

    public function getPasswordRequestAt(): ?DateTime
    {
        return $this->passwordRequestAt;
    }

    public function setPasswordRequestAt(?DateTime $passwordRequestAt): User
    {
        $this->passwordRequestAt = $passwordRequestAt;
        return $this;
    }

    public function getLastToken(): ?string
    {
        return $this->lastToken;
    }

    public function setLastToken(?string $lastToken): User
    {
        $this->lastToken = $lastToken;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): User
    {
        $this->active = $active;
        return $this;
    }

    public function getStaff(): ?Staff
    {
        return $this->staff;
    }

    public function setStaff(?Staff $staff): User
    {
        $this->staff = $staff;
        return $this;
    }
}