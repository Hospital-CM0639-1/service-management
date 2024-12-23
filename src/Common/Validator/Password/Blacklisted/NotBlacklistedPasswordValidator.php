<?php

namespace App\Common\Validator\Password\Blacklisted;

use App\Common\Entity\Password\BlacklistedPassword;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotBlacklistedPasswordValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof NotBlacklistedPassword) {
            throw new UnexpectedTypeException($constraint, NotBlacklistedPassword::class);
        }

        if (!is_string($value)) {
            # throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        # I verify if password contains an invalid combination
        $result = $this->doctrineHelper->createORMQueryBuilder()
            ->select('1')
            ->from(BlacklistedPassword::class, 'bp')
            ->andWhere("lower(:password) like concat('%', lower(bp.password), '%')")
            ->setParameter('password', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!is_null($result)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}