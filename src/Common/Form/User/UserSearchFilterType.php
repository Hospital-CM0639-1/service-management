<?php

namespace App\Common\Form\User;

use App\Common\Enum\Staff\StaffRoleEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Form\Misc\CheckboxTypeAdderTrait;
use App\Common\Model\Form\User\UserSearchFilter;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UserSearchFilterType extends AbstractType
{
    use CheckboxTypeAdderTrait;

    private readonly LoggedUserHelper $loggedUserHelper;

    #[Required]
    public function setLoggedUserHelper(LoggedUserHelper $loggedUserHelper): UserSearchFilterType
    {
        $this->loggedUserHelper = $loggedUserHelper;
        return $this;
    }

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

        $userTypeVisibleToLoggedUser = UserTypeCodeEnum::getTypesVisibleToType(
            userType: $this->loggedUserHelper->getLoggedUser()->getType()->getCode()
        );

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => array_combine(
                    $userTypeVisibleToLoggedUser,
                    $userTypeVisibleToLoggedUser
                )
            ])
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