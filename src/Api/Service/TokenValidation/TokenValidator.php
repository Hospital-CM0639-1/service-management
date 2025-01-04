<?php

namespace App\Api\Service\TokenValidation;

use App\Api\Enum\Error\ApiErrorCodeEnum;
use App\Api\Error\TokenValidation\TokenValidationException;
use App\Api\Model\TokenValidation\TokenValidationResult;
use App\Common\Entity\User;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

readonly class TokenValidator
{
    public function __construct(
        private JWTTokenManagerInterface $tokenManager,
        private DoctrineHelper $doctrineHelper
    ) {
    }

    /**
     * Validates the user token for api gateway.
     *
     * @param Request $request The HTTP request containing the user token.
     * @return TokenValidationResult The result of validation
     */
    public function validateUserTokenForApiGateway(Request $request): TokenValidationResult
    {
        return $this->validateUserToken(request: $request);
    }

    /**
     * Validates the user token for another application service.
     *
     * @param Request $request The incoming HTTP request containing the token to validate.
     * @return TokenValidationResult The result of validation
     */
    public function validateUserTokenForService(Request $request): TokenValidationResult
    {
        return $this->validateUserToken(request: $request);
    }

    /**
     * Validates a user token extracted from the request.
     *
     * This method retrieves the token from the request, validates its presence,
     * parses the token, checks for expiration, and verifies that a corresponding
     * user exists in the database.
     *
     * @param Request $request The HTTP request containing the token to validate.
     * @return TokenValidationResult The result of validation
     */
    private function validateUserToken(Request $request): TokenValidationResult
    {
        try {
            $token = $request->request->get('token');

            # token not present in the request
            if (empty($token)) {
                throw new TokenValidationException(message: ApiErrorCodeEnum::TOKEN_VALIDATION_001);
            }

            try {
                $payload = $this->tokenManager->parse(token: (string) $token);

            } catch (Throwable) {
                # invalid token
                throw new TokenValidationException(message: ApiErrorCodeEnum::TOKEN_VALIDATION_002);
            }

            # if token is expired
            if ($payload['exp'] < time()) {
                throw new TokenValidationException(message: ApiErrorCodeEnum::TOKEN_VALIDATION_003, httpStatusCode: Response::HTTP_UNAUTHORIZED);
            }

            # try to find user in DB
            /** @var ?User $user */
            $user = $this->doctrineHelper->getRepository(User::class)->findOneBy(['username' => $payload['username']]);
            if (is_null($user)) {
                throw new TokenValidationException(message: ApiErrorCodeEnum::TOKEN_VALIDATION_004, httpStatusCode: Response::HTTP_UNAUTHORIZED);
            }

            # if expired
            if ($user->getLastToken() !== $token) {
                throw new TokenValidationException(message: ApiErrorCodeEnum::TOKEN_VALIDATION_003, httpStatusCode: Response::HTTP_UNAUTHORIZED);
            }

            # if disable
            if (!$user->isActive()) {
                throw new TokenValidationException(message: ApiErrorCodeEnum::TOKEN_VALIDATION_005, httpStatusCode: Response::HTTP_UNAUTHORIZED);
            }

            $result = new TokenValidationResult(valid: true, user: $user);
        } catch (TokenValidationException $e) {
            $result = new TokenValidationResult(valid: false, invalidReason: $e->getMessage());
        }

        return $result;

    }
}