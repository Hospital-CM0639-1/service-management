<?php

namespace App\Common\Validator\Password\Repeated;

use App\Common\Entity\Password\UserPasswordHistory;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotRepeatedPasswordValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
        private readonly int $repeatedPasswordTtl
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof NotRepeatedPassword) {
            throw new UnexpectedTypeException($constraint, NotRepeatedPassword::class);
        }

        if (!is_string($value)) {
            # throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        # last X password used by user
        $lastPasswords = $this->doctrineHelper->createORMQueryBuilder()
            ->select('up.password')
            ->from(UserPasswordHistory::class, 'up')
            ->andWhere('up.user = :user')
            ->setParameter('user', $constraint->user)
            ->setMaxResults($this->repeatedPasswordTtl)
            ->orderBy('up.id', 'DESC')
            ->getQuery()
            ->getSingleColumnResult();

        # i check if the new password is equals to one of the last X user's passwords
        foreach ($lastPasswords as $lastPassword) {
            if (password_verify(password: $value, hash: $lastPassword)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->addViolation();
            }
        }

    }
}