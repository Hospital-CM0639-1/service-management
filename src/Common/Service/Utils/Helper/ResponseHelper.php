<?php

namespace App\Common\Service\Utils\Helper;

use App\Common\Enum\Error\CommonErrorCodeEnum;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ResponseHelper
{
    public function __construct(
        private SerializeHelper $serializeHelper
    ) {}

    public function makeDataJsonResponse(mixed $data, array $groups = [], int $statusCode = Response::HTTP_OK, string $format = 'json'): JsonResponse
    {
        return JsonResponse::fromJsonString(
            data: $this->serializeHelper->serialize(
                data: $data,
                groups: $groups,
                format: $format
            ),
            status: $statusCode
        );
    }

    public function makeFormErrorJsonResponse(FormInterface $form, int $statusCode = Response::HTTP_BAD_REQUEST, string $format = 'json'): JsonResponse
    {
        return JsonResponse::fromJsonString(
            data: $this->serializeHelper->serialize(
                data: ['message' => $form->getErrors(true)->current()->getMessage()],
                format: $format
            ),
            status: $statusCode
        );
    }

    public function makeErrorMessageJsonResponse(string $message = CommonErrorCodeEnum::DEFAULT_400, int $statusCode = Response::HTTP_BAD_REQUEST, string $format = 'json'): JsonResponse
    {
        return JsonResponse::fromJsonString(
            data: $this->serializeHelper->serialize(
                data: ['message' => $message],
                format: $format
            ),
            status: $statusCode
        );
    }

    public function makeEmptyJsonResponse(int $statusCode = Response::HTTP_OK, string $format = 'json'): JsonResponse
    {
        return $this->makeDataJsonResponse(
            data: [],
            statusCode: $statusCode,
            format: $format
        );
    }
}