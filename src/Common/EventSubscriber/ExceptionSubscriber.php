<?php

namespace App\Common\EventSubscriber;

use App\Common\Enum\Error\CommonErrorCodeEnum;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private bool $debug
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['convertExceptionToJsonResponse', -32],
            ],
        ];
    }

    public function convertExceptionToJsonResponse(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logException($exception, sprintf('Uncaught PHP Exception %s: "%s" at %s line %s', \get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine()));
        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();

            $message = match ($code) {
                Response::HTTP_INTERNAL_SERVER_ERROR => CommonErrorCodeEnum::DEFAULT_500,
                Response::HTTP_METHOD_NOT_ALLOWED => CommonErrorCodeEnum::DEFAULT_405,
                Response::HTTP_NOT_FOUND => CommonErrorCodeEnum::DEFAULT_404,
                Response::HTTP_FORBIDDEN => CommonErrorCodeEnum::DEFAULT_403,
                Response::HTTP_UNAUTHORIZED => CommonErrorCodeEnum::DEFAULT_401,
                default => CommonErrorCodeEnum::DEFAULT_000
            };
        } else {
            $message = CommonErrorCodeEnum::DEFAULT_500;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($this->debug) {
            $responseData = [
                'trace' => $this->prepareTraceForJsonEncoding($exception),
                'code' => $exception->getCode(),
                'message' => $message,
                'errorMessage' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        } else {
            $responseData = [
                'code' => $code,
                'message' => $message,
            ];
        }

        $responseJsonData = json_encode(
            $responseData,
            JSON_PRETTY_PRINT
        );

        if (JSON_ERROR_NONE !== json_last_error()) {
            return;
        }

        $response = JsonResponse::fromJsonString(
            $responseJsonData,
            $code
        );

        $event->setResponse($response);
    }

    /**
     * @param \Exception $exception
     *
     * @return array|string
     */
    private function prepareTraceForJsonEncoding($exception): array|string
    {
        $trace = $exception->getTrace();
        $reversedTrace = array_reverse($trace);
        json_encode($reversedTrace);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return $exception->getTraceAsString();
        }

        return $reversedTrace;
    }

    /**
     * Logs an exception.
     *
     * @param \Throwable $exception The \Exception instance
     * @param string     $message   The error message to log
     */
    protected function logException(\Throwable $exception, string $message): void
    {
        if (null !== $this->logger) {
            if (!$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= Response::HTTP_INTERNAL_SERVER_ERROR) {
                $this->logger->critical($message, ['exception' => $exception]);
            } else {
                $this->logger->error($message, ['exception' => $exception]);
            }
        }
    }
}
