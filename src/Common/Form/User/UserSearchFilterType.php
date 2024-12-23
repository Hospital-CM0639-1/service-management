<?php

namespace App\Common\Form\User;

use App\Common\Enum\Staff\StaffRoleEnum;
use App\Common\Form\Misc\CheckboxTypeAdderTrait;
use App\Common\Model\User\UserSearchFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchFilterType extends AbstractType
{
    use CheckboxTypeAdderTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCheckboxField(
            builder: $builder,
            options: $options,
            fieldName: 'status',
            trueValue: 'active',
            falseValue: 'not_active',
            nullValue: 'all'
        );
        $builder
            ->add('role', ChoiceType::class, [
                'choices' => array_combine(
                    StaffRoleEnum::getAllStaffRoles(),
                    StaffRoleEnum::getAllStaffRoles()
                )
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'allow_extra_fields' => true,
                'data_class' => UserSearchFilter::class,
            ]);
    }

}