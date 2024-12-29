<?php

namespace App\Common\Form\User;

use App\Common\Entity\User;
use App\Common\Entity\UserType\UserType;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Regex\User\UserRegex;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUserType extends EditUserType
{
    private readonly DoctrineHelper $doctrineHelper;
    private readonly LoggedUserHelper $loggedUserHelper;

    #[Required]
    public function setDoctrineHelper(DoctrineHelper $doctrineHelper): CreateUserType
    {
        $this->doctrineHelper = $doctrineHelper;
        return $this;
    }

    #[Required]
    public function setLoggedUserHelper(LoggedUserHelper $loggedUserHelper): CreateUserType
    {
        $this->loggedUserHelper = $loggedUserHelper;
        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', EntityType::class, [
                'class' => UserType::class
            ])
            ->add('username', null, [
                'constraints' => [
                    new Regex(
                        pattern: UserRegex::USERNAME,
                        message: CommonErrorCodeEnum::USER_002
                    )
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();
                $userTypeCode = $data['type'] ?? null;
                $userType = $this->doctrineHelper->getRepository(UserType::class)->findOneBy(['code' => $userTypeCode]);

                $loggedUser = $this->loggedUserHelper->getLoggedUser();
                if (
                    # user type not found
                    is_null($userType)

                    # user type not supported for logged user
                    || !in_array($userTypeCode, UserTypeCodeEnum::getTypesVisibleToType(userType: $loggedUser->getType()->getCode()), true)
                ) {
                    $event->getForm()->addError(new FormError(message: CommonErrorCodeEnum::DEFAULT_400));
                    return;
                }

                $data['type'] = $userType->getId();
                $event->setData($data);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefaults([
                'constraints' => [
                    new UniqueEntity(
                        fields: ['username'],
                        message: CommonErrorCodeEnum::USER_004,
                        entityClass: User::class,
                    )
                ]
            ]);
    }


}