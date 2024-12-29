<?php

namespace App\Common\Form\User;

use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new Email(
                        message: CommonErrorCodeEnum::USER_001,
                        mode: Email::VALIDATION_MODE_HTML5
                    )
                ]
            ])
            ->add('firstName', null, [
                'property_path' => 'name',
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ])
            ->add('lastName', null, [
                'property_path' => 'surname',
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'allow_extra_fields' => true,
                'data_class' => User::class,
            ]);
    }

}