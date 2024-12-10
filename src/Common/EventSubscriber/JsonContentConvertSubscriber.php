<?php

namespace App\Common\EventSubscriber;

use App\Common\Service\Utils\Helper\JsonContentHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JsonContentConvertSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['convertJsonContent', 4096]],
        ];
    }

    /**
     * Convertion of content request to be elaborated
     *
     * @param RequestEvent $event
     */
    public function convertJsonContent(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $request->request = $this->createPostParameterBag($request);
        $request->query = $this->createGetParameterBag($request);
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    private function createPostParameterBag(Request $request): ParameterBag
    {
        if ($request->getContent()) {
            $arrayContent = JsonContentHelper::convertJsonContent(content: $request->getContent(), strict: false);
        } else {
            $arrayContent = JsonContentHelper::convertJsonContent(content: $request->request->all(), strict: false);
        }
        if (!\is_array($arrayContent)) {
            return $request->request;
        }

        return new ParameterBag($arrayContent);
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    private function createGetParameterBag(Request $request): ParameterBag
    {
        return new ParameterBag(
            JsonContentHelper::convertJsonContent(content: $request->query->all(), strict: false)
        );
    }

}
