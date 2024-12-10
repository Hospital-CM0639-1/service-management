<?php

namespace App\Common\EventSubscriber\RequestLog;

use App\Common\Entity\RequestLog\RequestLog;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use App\Common\Service\Utils\Helper\LoggedUserHelper;
use Doctrine\DBAL\ParameterType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class RequestLogSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggedUserHelper $loggedUserHelper,
        private DoctrineHelper   $doctrineHelper
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['saveRequest', 2048]],
            KernelEvents::RESPONSE => [['saveResponse', 2048]],
        ];
    }

    /**
     * Creo il request log con tutto il contenuto della richiesta
     *
     * @param RequestEvent $event
     */
    public function saveRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMainRequest() || $request->getMethod() === 'OPTIONS' || str_contains($request->getPathInfo(), '/_profiler/')) {
            return;
        }

        $requestLog = (new RequestLog())
            ->setUsername($this->loggedUserHelper->getLoggedUser()?->getUsername())
            ->setToken(substr($request->headers->get('Authorization'), strlen("Bearer ")))
            ->setBodyParams(json_encode($request->request->all()))
            ->setQueryParams(json_encode($request->query->all()))
            ->setContent($request->getContent())
            ->setPathInfo($request->getPathInfo())
            ->setUrl($request->getUri());

        $this->doctrineHelper->save($requestLog);
        $request->attributes->set('request_log', $requestLog);
    }

    /**
     * Salvo la risposta
     *
     * @param ResponseEvent $event
     * @return void
     */
    public function saveResponse(ResponseEvent $event): void
    {
        /** @var ?RequestLog $request */
        $requestLog = $event->getRequest()->attributes->get('request_log');

        if (!is_null($requestLog)) {
            $response = $event->getResponse();

            $this->doctrineHelper
                ->createORMQueryBuilder()
                ->update(RequestLog::class, 'rl')
                ->set('rl.response', ':response')
                ->set('rl.endedAt', ':endedAt')
                ->set('rl.responseStatusCode', ':responseStatusCode')
                ->setParameter('response', $response->getContent())
                ->setParameter('endedAt', (new \DateTime())->format('Y-m-d H:i:s'))
                ->setParameter('responseStatusCode', $response->getStatusCode())
                ->andWhere('rl = :requestLog')
                ->setParameter('requestLog', $requestLog)
                ->getQuery()
                ->execute();
        }
    }
}