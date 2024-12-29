<?php

namespace App\Common\Form\Patient;

use App\Common\Entity\Patient\Patient;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\Patient\PatientGenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateOfBirth', DateType::class, [
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => array_combine(
                    PatientGenderEnum::getAllValues(),
                    PatientGenderEnum::getAllValues()
                )
            ])
            ->add('contactNumber', null, [
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ])
            ->add('emergencyContactName', null, [
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ])
            ->add('emergencyContactNumber', null, [
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ])
            ->add('address', null, [
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ])
            ->add('insuranceProvider', null, [
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ])
            ->add('insurancePolicyNumber', null, [
                'constraints' => [
                    new NotBlank(message: CommonErrorCodeEnum::DEFAULT_001, allowNull: false)
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Patient::class
            ]);
    }
}