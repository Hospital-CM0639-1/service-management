<?php
namespace App\Common\Controller\Password;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\Password\UserPasswordHistory;
use App\Common\Entity\User;
use App\Common\Enum\Error\CommonErrorCodeEnum;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Form\Password\ChangePasswordType;
use App\Common\Model\Form\Password\ChangePassword;
use App\Common\Security\Voter\Password\CanChangePasswordToUserVoter;
use App\Common\Security\Voter\User\CanViewUserVoter;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ChangePasswordController extends Controller
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    #[Route(path: '/user/change-password', name: 'user_change_password', methods: ['POST'])]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
            UserTypeCodeEnum::PATIENT,
        ]
    )]
    public function userChangePassword(Request $request): Response
    {
        $loggedUser = $this->getUser();
        return $this->processPasswordChange(
            request: $request,
            user: $loggedUser,
            requireOldPassword: true
        );
    }

    #[Route(path: '/user/{userToChangePassword}/change-password', name: 'user_change_password_to_another_one', methods: ['POST'])]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::ADMIN,
            UserTypeCodeEnum::STAFF,
        ]
    )]
    #[IsGranted(
        attribute: CanViewUserVoter::COMMON_CAN_VIEW_USER,
        subject: 'userToChangePassword',
        message: CommonErrorCodeEnum::DEFAULT_404,
        statusCode: Response::HTTP_NOT_FOUND
    )]
    #[IsGranted(
        attribute: CanChangePasswordToUserVoter::CAN_CHANGE_PASSWORD_TO_USER,
        subject: 'userToChangePassword',
        message: CommonErrorCodeEnum::DEFAULT_403,
    )]
    public function userChangePasswordToAnotherOne(Request $request, User $userToChangePassword): Response
    {
        return $this->processPasswordChange(
            request: $request,
            user: $userToChangePassword,
            requireOldPassword: false
        );
    }

    private function processPasswordChange(Request $request, User $user, bool $requireOldPassword): Response
    {
        $loggedUser = $this->getUser();
        $changePassword = new ChangePassword();
        $form = $this->createForm(
            type: ChangePasswordType::class,
            data: $changePassword,
            options: [
                'user' => $user,
                'required_old_password' => $requireOldPassword,
            ]
        );

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $this->makeFormErrorJsonResponse(form: $form);
        }

        # Hash the new password
        $newPasswordHash = $this->passwordHasher->hashPassword(user: $user, plainPassword: $changePassword->getNewPassword());

        # Save the new password to history
        $userPasswordHistory = (new UserPasswordHistory())
            ->setUser($user)
            ->setPassword($newPasswordHash);

        # Update user and remove the current token
        $passwordChangedAt = $loggedUser->compareTo($user)
            ? new DateTime()
            : new DateTime(sprintf('-%d days', User::PASSWORD_TTL_IN_DAYS));
        $user
            ->setPasswordChangedAt($passwordChangedAt)
            ->setLastToken(null)
            ->setPassword($newPasswordHash);

        # Persist the updated entities
        $this->save($userPasswordHistory);

        return $this->makeEmptyJsonResponse();
    }
}