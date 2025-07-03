<?php
declare(strict_types=1);

namespace Novaway\ElasticsearchBundle\EventSubscriber;

use Elastica\Exception\ResponseException as ElasticaResponseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Novaway\ElasticsearchBundle\Exception\Response\ResponseException;

class ResponseExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                'process'
            ]
        ];
    }
    public function process(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof ElasticaResponseException) {
            return;
        }
        $response = new ResponseException($exception->getRequest(), $exception->getResponse());
        $event->setThrowable($response);
    }
}
