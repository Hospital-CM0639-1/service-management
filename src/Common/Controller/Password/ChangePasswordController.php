<?php

namespace App\Common\Controller\Password;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\Password\UserPasswordHistory;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Form\Common\Password\ChangePasswordType;
use App\Common\Model\Password\ChangePassword;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ChangePasswordController extends Controller
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    #[Route(path: '/user/change-password', name: 'user_change_password', methods: ['POST'])]
    #[AllowedUserType(allowedUserTypes: [UserTypeCodeEnum::ADMIN])]
    public function userChangePassword(Request $request): Response
    {
        $loggedUser = $this->getUser();

        $changePassword = new ChangePassword();
        $form = $this->createForm(
            type: ChangePasswordType::class,
            data: $changePassword,
            options: [
                'user' => $loggedUser,
                'required_old_password' => true,
            ]
        );

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $this->makeFormErrorJsonResponse(form: $form);
        }

        # hash password
        $hashedPassword = $this->passwordHasher->hashPassword(user: $this->getUser(), plainPassword: $changePassword->getNewPassword());

        # save new password
        $userPassword = (new UserPasswordHistory())
            ->setUser($loggedUser)
            ->setPassword($hashedPassword);

        # update password and remove current token to force user logout
        $loggedUser
            ->setLastToken(null)
            ->setPassword($hashedPassword);

        $this->save($userPassword);

        return $this->makeEmptyJsonResponse();
    }
}