<?php

namespace App\Common\Controller\User;

use App\Common\Attribute\Security\AllowedUserType;
use App\Common\Controller\Controller;
use App\Common\Entity\Patient\Patient;
use App\Common\Entity\User;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetUserPatientIdController extends Controller
{
    #[Route(
        path: '/user/{patient}/patient-id',
        name: 'user_patient_id',
        requirements: ['patient' => '\d+'],
        methods: ['GET']
    )]
    #[AllowedUserType(
        allowedUserTypes: [
            UserTypeCodeEnum::STAFF,
        ]
    )]
    public function userPatientId(Patient $patient): Response
    {
        $user = $this->getRepository(User::class)->findOneBy(['patient' => $patient]);
        return $this->makeDataJsonResponse(
            data: ["id" => $user?->getId()],
        );
    }
}