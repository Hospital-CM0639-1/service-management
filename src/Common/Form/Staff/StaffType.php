<?php

namespace App\Common\Form\Staff;

use App\Common\Entity\Staff\Staff;
use App\Common\Enum\Staff\StaffRoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', ChoiceType::class, [
                'choices' => array_combine(
                    StaffRoleEnum::getAllStaffRoles(),
                    StaffRoleEnum::getAllStaffRoles()
                )
            ])
            ->add('department')
            ->add('specialization')
            ->add('phoneNumber')
            ->add('hireDate', DateType::class, [
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Staff::class
            ]);
    }

}