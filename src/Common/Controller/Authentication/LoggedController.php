<?php

namespace App\Common\Controller\Authentication;

use App\Common\Controller\Controller;
use App\Common\Serializer\Entity\User\UserGroupSerializer;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoggedController extends Controller
{
    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager,
    ) {}

    #[Route(path: '/logged', name: 'logged', methods: ['GET'])]
    public function logged(): Response
    {
        $loggedUser = $this->getUser();

        # creo un nuovo token e lo setto nell'utente
        $loggedUser->setLastToken($this->tokenManager->create(user: $loggedUser));
        $this->save();

        return $this->makeDataJsonResponse(data: $loggedUser, groups: UserGroupSerializer::loggedUser());
    }
}