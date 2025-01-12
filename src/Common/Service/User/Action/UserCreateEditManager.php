<?php

namespace App\Common\Service\User\Action;

use App\Common\Entity\Password\UserPasswordHistory;
use App\Common\Entity\Patient\Patient;
use App\Common\Entity\Staff\Staff;
use App\Common\Entity\User;
use App\Common\Error\FormException;
use App\Common\Form\Patient\PatientType;
use App\Common\Form\Staff\StaffType;
use App\Common\Form\User\CreateUserType;
use App\Common\Form\User\EditUserType;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserCreateEditManager
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private FormFactoryInterface $formFactory,
        private UserPasswordHasherInterface $passwordHasher,
        private string $defaultPassword
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
            $this->manageStaffInfo(request: $request, user: $userToManage);
        }

        # only if user is patient type
        if ($userToManage->isPatient()) {
            $this->managePatientInfo(request: $request, user: $userToManage);
        }

        # Hash the new password
        $newPasswordHash = $this->passwordHasher->hashPassword(user: $userToManage, plainPassword: $this->defaultPassword);

        # Save the new password to history
        $userPasswordHistory = (new UserPasswordHistory())
            ->setUser($userToManage)
            ->setPassword($newPasswordHash);

        # Update user and remove the current token
        $userToManage
            ->setPassword($newPasswordHash);

        # Persist the updated entities
        $this->doctrineHelper->save($userPasswordHistory);
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
            $this->manageStaffInfo(request: $request, user: $userToManage);
        }

        # only if user is patient type
        if ($userToManage->isPatient()) {
            $this->managePatientInfo(request: $request, user: $userToManage);
        }
    }

    /**
     * Manage patient entity info
     *
     * @param Request $request
     * @param User    $user
     * @return void
     * @throws FormException
     */
    private function managePatientInfo(Request $request, User $user): void
    {
        $patient = $user->getPatient();
        if (is_null($patient)) {
            $patient = new Patient();
            $user->setPatient($patient);
        }

        $patient
            ->setLastUpdated(new \DateTime())
            ->setEmail($user->getEmail())
            ->setFirstName($user->getName())
            ->setLastName($user->getSurname());

        $form = $this->formFactory->create(PatientType::class, $patient);

        $form->submit($request->request->get('patientInfo', []));
        if (!$form->isValid()) {
            throw new FormException(form: $form);
        }

        $this->doctrineHelper->save($patient);
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
        if (is_null($staff)) {
            $staff = new Staff();
            $user->setStaff($staff);
        }

        $staff
            ->setEmail($user->getEmail())
            ->setFirstName($user->getName())
            ->setLastName($user->getSurname());

        $form = $this->formFactory->create(StaffType::class, $staff);

        $form->submit($request->request->get('staffInfo', []));
        if (!$form->isValid()) {
            throw new FormException(form: $form);
        }

        $this->doctrineHelper->save($staff);
    }
}