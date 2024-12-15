<?php

namespace App\Api\Controller\TokenValidation;

use App\Api\Attribute\ContainsApiToken;
use App\Api\Serializer\TokenValidation\TokenValidationResultGroupSerializer;
use App\Api\Service\TokenValidation\TokenValidator;
use App\Common\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ValidateUserTokenController extends Controller
{
    public function __construct(
        private readonly TokenValidator $tokenValidator
    ) {}

    #[Route(path: '/api/gateway/validate-user-token', name: 'gateway_validate_user_token', methods: ['POST'])]
    #[ContainsApiToken]
    public function gatewayValidateUserToken(Request $request): JsonResponse
    {
        $result = $this->tokenValidator->validateUserTokenForApiGateway(request: $request);

        return $this->makeDataJsonResponse(
            data: $result,
            groups: TokenValidationResultGroupSerializer::apiGateway(),
            statusCode: $result->isValid() ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED
        );
    }

    #[Route(path: '/api/service/validate-user-token', name: 'service_validate_user_token', methods: ['POST'])]
    #[ContainsApiToken]
    public function serviceValidateUserToken(Request $request): JsonResponse
    {
        $result = $this->tokenValidator->validateUserTokenForService(request: $request);

        return $this->makeDataJsonResponse(
            data: $result,
            groups: TokenValidationResultGroupSerializer::service(),
            statusCode: $result->isValid() ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED
        );
    }

}