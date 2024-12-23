<?php

namespace App\Common\Service\User\Action;

use App\Common\Entity\Staff\Staff;
use App\Common\Entity\User;
use App\Common\Error\FormException;
use App\Common\Form\Staff\StaffType;
use App\Common\Form\User\CreateUserType;
use App\Common\Form\User\EditUserType;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class UserCreateEditManager
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private FormFactoryInterface $formFactory,
    ) {}

    /**
     * Manage user creation
     *
     * @param Request $request
     * @param User    $userToManage
     * @return void
     * @throws FormException
     */
    public function manageOnCreateUser(Request $request, User $userToManage): void
    {
        $form = $this->formFactory->create(CreateUserType::class, $userToManage);

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new FormException(form: $form);
        }

        $this->doctrineHelper->save($userToManage);

        # only if user is staff type
        if ($userToManage->isStaff()) {
            $staff = (new Staff())
                ->setEmail($userToManage->getEmail())
                ->setFirstName($userToManage->getName())
                ->setLastName($userToManage->getSurname());
            $userToManage->setStaff($staff);
            $this->manageStaffInfo(request: $request, user: $userToManage);
        }
    }

    /**
     * Manage edit user
     *
     * @param Request $request
     * @param User    $userToManage
     * @return void
     * @throws FormException
     */
    public function manageOnEditUser(Request $request, User $userToManage): void
    {
        $form = $this->formFactory->create(EditUserType::class, $userToManage);

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new FormException(form: $form);
        }

        $this->doctrineHelper->save($userToManage);
        # only if user is staff type
        if ($userToManage->isStaff()) {
            $userToManage
                ->getStaff()
                ->setFirstName($userToManage->getName())
                ->setLastName($userToManage->getSurname());
            $this->manageStaffInfo(request: $request, user: $userToManage);
        }
    }

    /**
     * Manage staff entity info
     *
     * @param Request $request
     * @param User    $user
     * @return void
     * @throws FormException
     */
    private function manageStaffInfo(Request $request, User $user): void
    {
        $staff = $user->getStaff();
        $form = $this->formFactory->create(StaffType::class, $staff);

        $form->submit($request->request->get('staffInfo', []));
        if (!$form->isValid()) {
            throw new FormException(form: $form);
        }

        $this->doctrineHelper->save($staff);
    }
}