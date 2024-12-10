<?php

namespace App\Common\Controller\Authentication;

use App\Common\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogoutController extends Controller
{
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        $this->getUser()->setLastToken(null);
        $this->save();
        return $this->makeEmptyJsonResponse();
    }
}
