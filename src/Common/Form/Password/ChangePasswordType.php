<?php

namespace App\Common\Form\Password;

use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Model\Form\Password\ChangePassword;
use App\Common\Regex\Password\PasswordRegex;
use App\Common\Validator\Password\Blacklisted\NotBlacklistedPassword;
use App\Common\Validator\Password\Repeated\NotRepeatedPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['required_old_password']) {
            $builder
                ->add('oldPassword', PasswordType::class, [
                    'constraints' => [
                        new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false),
                        new UserPassword(message: CommonErrorCodeEnum::PASSWORD_005)
                    ]
                ]);
        }
        $builder
            ->add('newPassword', PasswordType::class, [
                'constraints' => [
                    new NotBlacklistedPassword(user: $options['user']),
                    new NotRepeatedPassword(user: $options['user']),
                    new Regex(
                        pattern: PasswordRegex::USER_PASSWORD,
                        message: CommonErrorCodeEnum::PASSWORD_002
                    )
                ]
            ])
            ->add('repeatedPassword', PasswordType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, static function (FormEvent $event): void {
                /** @var ChangePassword $changePassword */
                $changePassword = $event->getData();

                # check if password and repeated one are equals
                if ($changePassword->getNewPassword() !== $changePassword->getRepeatedPassword()) {
                    $event->getForm()->addError(new FormError(message: CommonErrorCodeEnum::PASSWORD_001));
                    return;
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => ChangePassword::class,
            ])
            ->setRequired('required_old_password')
            ->setAllowedTypes('required_old_password', 'bool')
            ->setRequired('user')
            ->setAllowedTypes('user', User::class);
    }

}